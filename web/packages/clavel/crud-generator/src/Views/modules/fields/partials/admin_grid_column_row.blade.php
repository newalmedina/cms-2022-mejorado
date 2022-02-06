
<div data="{{ $item->col }}" class="col-{{ (12 / $item->max_col) }} column sortable">
    {!! Form::hidden('row['.$item->row.'][columns][]', $item->col, ['id' => 'row['.$item->row.'][columns][]']) !!}

    <div class="blocks panel panel-default panel-body">
        @if(!empty($item->field))

            <div id="{{ $item->field->id }}" class="block clearfix">

                <div class="block-actions float-right">
                    <div class="remove modifier remove-block">
                        <i class="fa fa-times"></i>
                    </div>
                </div>

                {!! Form::hidden('item['.$item->field->id.'][id]', $item->field->id, ['id' => 'item['.$item->field->id.'][id]']) !!}
                {!! Form::hidden('item['.$item->field->id.'][row]',$item->row, ['id' => 'item['.$item->field->id.'][row]']) !!}
                {!! Form::hidden('item['.$item->field->id.'][column]',$item->col, ['id' => 'item['.$item->field->id.'][column]']) !!}

                <span class="handle ui-sortable-handle">
                    <i class="fas fa-arrows-alt text-info icon-move"></i>
                </span>
                <span class="text">{{ $item->field->column_title  }}</span>
                <small class="badge badge-warning">{{ $item->field->column_name }}</small>
                <span class="badge badge-success">{{ $item->field->type->name }}</span>

            </div>
        @endif
    </div>
</div>
