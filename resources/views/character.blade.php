@extends('layout')

@section('title')
    @if (empty($character))
        No Character Found
    @else
        {{$character->name}}-{{$character->realm}}
    @endif
@endsection

@section('content')
    <div align="center">
        @if (empty($character))
            <h3 style="font-weight:bold">No Character Found</h3>
        @else
            <?php $className = "UNKNOWN"; ?>
            @foreach ($classes->classes as $class)
                @if ($class->id == $character->class)
                    <?php $className = $class->name; break; ?>
                @endif
            @endforeach
            <?php $specName = "UNKNOWN"; ?>
            @foreach ($character->talents as $talent)
                @if (isset($talent->selected))
                    <?php $specName = $talent->spec->name; break; ?>
                @endif
            @endforeach
            
            <h3 style="text-decoration:underline">{{$character->name}}-{{$character->realm}}</h3>
            <h4>{{$specName}} {{$className}}</h4>
            <table class="table table-striped table-hover" style="width:80%" align="center">
                <tr>
                    <th class="text-center">Slot</th>
                    <th class="text-center">Item</th>
                </tr>
                @include('item-display')
            </table>
            <form action="/saved" method="post">
                {{csrf_field()}}
                <input type="hidden" name="name" value="{{$character->name}}">
                <input type="hidden" name="realm" value="{{$character->realm}}">
                <input type="hidden" name="class" value="{{$className}}">
                <input type="hidden" name="specialization" value="{{$specName}}">
                <button type="submit" class="btn btn-default">Save Character</button>
            </form>
        @endif
    </div>
@endsection
