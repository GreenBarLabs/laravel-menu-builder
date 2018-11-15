<ul class="{{ $menu->position }}" style="color: #000;">
    @php
        $currDepth = 0;
    @endphp

    @foreach($menu_items as $menu_item)
        @if($menu_item->depth > $currDepth)
            <li><ul>
        @endif

        @if($menu_item->depth < $currDepth)
    <li>{{ $menu_item->name }}</li>
    @endforeach
</ul>