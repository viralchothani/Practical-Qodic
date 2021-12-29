@foreach($subcategories as $val)
    <ul>
        <li id="{{ $val->category }}">{{$val->category}}</li> 
        @if(count($val->subcategory))
            @include('sub_category',['subcategories' => $val->subcategory])
        @endif
    </ul> 
@endforeach