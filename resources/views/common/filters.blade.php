{!! Form::open([ 'action' => $action, 'method' => 'get']) !!}
<div>
    @foreach($fields as $field)
        <div>
            <label>{{ $field['label'] }}</label>
        @switch($field['type'])
            @case('text')
                {!! Form::text("filters[{$field['name']}]", $field['value'], array('placeholder' => $field['label'],'class' => 'form-control')) !!}
            @endswitch
        </div>
    @endforeach
    <div>
        <button class="btn btn-info" name="filters[formButton]" value="find">Find</button>
        <button class="btn btn-info" name="filters[formButton]" value="clear">Clear</button>
    </div>
</div>
{!! Form::close() !!}
