import { marked } from "marked";

const MEETUP_URL = "https://www.meetup.com/Melbourne-CocoaHeads/";

/**
 * Extract all JSON-LD blocks from HTML and return parsed objects.
 */
function extractJsonLd(html) {
  const blocks = [];
  const pattern = /<script\s+type="application\/ld\+json"\s*>([\s\S]*?)<\/script>/gi;
  let match;
  while ((match = pattern.exec(html)) !== null) {
    try {
      blocks.push(JSON.parse(match[1]));
    } catch {
      // Skip malformed JSON-LD blocks
    }
  }
  return blocks;
}

/**
 * Recursively collect all Event objects from JSON-LD data,
 * handling both top-level objects and arrays.
 */
function collectEvents(data) {
  const events = [];
  if (Array.isArray(data)) {
    for (const item of data) {
      events.push(...collectEvents(item));
    }
  } else if (data && typeof data === "object") {
    if (data["@type"] === "Event") {
      events.push(data);
    }
  }
  return events;
}

/**
 * Convert a Meetup description (Markdown) to HTML using marked.
 *
 * Pre-processes Meetup-specific quirks (backslash escapes, headerless tables)
 * then delegates to marked for full Markdown rendering.
 */
function descriptionToHtml(text) {
  if (!text) return "";

  // Strip backslash escapes added by Meetup (e.g. \| \- \+ \. \*)
  text = text.replace(/\\([^\\])/g, "$1");

  // Meetup table rows lack a header — prepend one so marked renders a <table>
  text = text.replace(
    /(^|\n\n)((\|[^\n]+\|\n?){2,})/g,
    (_, prefix, tableBlock) => {
      // Only prepend header if the block doesn't already have a separator row
      if (/^\|[-|\s]+\|$/m.test(tableBlock)) return prefix + tableBlock;
      return prefix + "| Time | Item |\n|-|-|\n" + tableBlock;
    },
  );

  return marked.parse(text);
}

/**
 * Extract the venue name from a JSON-LD Event location.
 */
function venueName(event) {
  const loc = event.location;
  if (!loc) return null;
  if (typeof loc === "string") return loc;
  return loc.name || null;
}

export async function handler() {
  try {
    const res = await fetch(MEETUP_URL);
    if (!res.ok) {
      throw new Error(`Meetup returned HTTP ${res.status}`);
    }
    const html = await res.text();

    const jsonLdBlocks = extractJsonLd(html);
    const allEvents = jsonLdBlocks.flatMap(collectEvents);

    const now = new Date();
    const futureEvents = allEvents
      .filter((e) => e.startDate && new Date(e.startDate) > now)
      .sort((a, b) => new Date(a.startDate) - new Date(b.startDate));

    const result = futureEvents.map((e) => ({
      name: e.name,
      startDate: e.startDate,
      venue: venueName(e),
      link: e.url,
      description_html: descriptionToHtml(e.description),
    }));

    return {
      statusCode: 200,
      headers: {
        "Content-Type": "application/json",
        "Cache-Control": "public, max-age=300",
      },
      body: JSON.stringify(result),
    };
  } catch (err) {
    console.error("Error fetching Meetup events:", err);
    return {
      statusCode: 502,
      headers: {
        "Content-Type": "application/json",
        "Cache-Control": "no-cache",
      },
      body: JSON.stringify({ error: "Failed to fetch events from Meetup" }),
    };
  }
}
