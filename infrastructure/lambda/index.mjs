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
 * Convert a Meetup description (light Markdown) to HTML.
 *
 * Handles:
 * - **bold** -> <strong>bold</strong>
 * - Lines starting with bullet character -> <li> items wrapped in <ul>
 * - Bare URLs -> <a> links
 * - Blank-line-separated paragraphs -> <p> blocks
 */
function descriptionToHtml(text) {
  if (!text) return "";

  // Normalize line endings
  text = text.replace(/\r\n/g, "\n").replace(/\r/g, "\n");

  // Split into paragraphs on blank lines
  const paragraphs = text.split(/\n\n+/);

  const htmlParts = [];

  for (const para of paragraphs) {
    const lines = para.split("\n");

    // Check if this paragraph is a bullet list (all non-empty lines start with bullet)
    const nonEmpty = lines.filter((l) => l.trim().length > 0);
    const isList =
      nonEmpty.length > 0 &&
      nonEmpty.every((l) => /^\s*[•\-\*]\s/.test(l));

    if (isList) {
      const items = nonEmpty.map((l) => {
        const content = l.replace(/^\s*[•\-\*]\s*/, "");
        return `<li>${inlineFormat(content)}</li>`;
      });
      htmlParts.push(`<ul>${items.join("")}</ul>`);
    } else {
      // Regular paragraph — join lines with <br> for single newlines
      const joined = lines.map((l) => inlineFormat(l)).join("<br>");
      htmlParts.push(`<p>${joined}</p>`);
    }
  }

  return htmlParts.join("");
}

/**
 * Apply inline formatting: bold and auto-linked URLs.
 */
function inlineFormat(text) {
  // Escape HTML entities first (XSS prevention)
  text = text.replace(/&/g, "&amp;");
  text = text.replace(/</g, "&lt;");
  text = text.replace(/>/g, "&gt;");

  // Bold: **text**
  text = text.replace(/\*\*(.+?)\*\*/g, "<strong>$1</strong>");

  // Auto-link bare URLs that are not already inside an href
  text = text.replace(
    /(?<!")https?:\/\/[^\s<)]+/g,
    (url) => `<a href="${url}" rel="noopener noreferrer">${url}</a>`
  );

  return text;
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
