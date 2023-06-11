@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Blast')
<img src="https://blst.to/images/logos/notification-logo.png" class="logo" alt="Blast Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
