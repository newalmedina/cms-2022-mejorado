<div class="row-data" data="{{ $item->row }}">
    {!! Form::hidden('row['.$item->row.'][id]', $item->row, ['id' => 'row['.$item->row.'][id]']) !!}
    <div class="row-toolbar col-12 unsortable">

        <div class="column-selector">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                <i class="fas fa-columns"></i>
            </button>
            <ul class="dropdown-menu row-columns dropdown">
                <li role="presentation" class="column-option" data-split="1"><a role="menuitem" tabindex="-1" href="#">12</a></li>
                <li role="presentation" class="column-option" data-split="2,2"><a role="menuitem" tabindex="-1" href="#">6/6</a></li>
                <li role="presentation" class="column-option" data-split="3,3,3"><a role="menuitem" tabindex="-1" href="#">4/4/4</a></li>
                <li role="presentation" class="column-option" data-split="4,4,4,4"><a role="menuitem" tabindex="-1" href="#">3/3/3/3</a></li>
            </ul>

        </div>
        <div class="row-actions float-right">
            <div class="remove remove-row">
                <button class="btn btn-danger" type="button">
                    <i class="fas fa-times"></i>
                </button>

            </div>
        </div>
    </div>

    <div class="panel panel-default panel-body panel-container sortable">
        <div class="column-container row">
