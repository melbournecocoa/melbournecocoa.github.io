@extends('layouts.email')

@section('title', $subject)

@section('content')

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    @foreach($paragraphs as $p)
    <tr>
        <td align="left" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">{{ $p }}</td>
    </tr>
    @endforeach
</table>

@endsection