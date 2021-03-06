<h4>{{$slot}}</h4>
<table class="table table-striped table-hover" style="width:80%" align="center">
    <tr>
        <th class="text-center">Item</th>
        <th class="text-center">Count</th>
        <th class="text-center">Characters</th>
    </tr>
    @if (!empty($items))
        <?php $counts = array_count_values($items); ?>
        <?php $items = array_unique($items); ?>
        @foreach ($items as $item)
            @include('compare-item', array('id' => $item, 'name' => $names[$item], 'count' => $counts[$item], 'characterNames' => $charactersWithItem[$item]))
        @endforeach
    @else
        @include('compare-item')
    @endif
</table>
<br>