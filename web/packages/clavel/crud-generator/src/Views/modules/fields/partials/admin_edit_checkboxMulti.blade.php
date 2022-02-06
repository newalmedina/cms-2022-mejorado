
<div class="card card-primary">
    <div class="card-header  with-border"><h3 class="card-title">{{ trans("crud-generator::fields/admin_lang.extra_options") }} - Multi checkbox</h3></div>
    <div class="card-body">
        <div class="row d-flex justify-content-end mb-2 mr-2">
            <a href="#" id="addRow" class="btn btn-info float-right"><i class="fa fa-plus" aria-hidden="true"></i> Añadir opción</a>
        </div>

        <table id="table_options" class="table table-bordered table-striped" style="width:100%" aria-hidden="true">
            <thead>
            <tr>
                <th scope="col">Valor</th>
                <th scope="col">Texto</th>
                <th scope="col">Acción</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($field->data))
                @foreach(json_decode($field->data) as $select)
                    <tr>
                        <td>
                            {!! Form::text('checkboxMulti_data[]', $select[0],
                        array('placeholder' => 'Valor',
                        'class' => 'form-control input-xlarge',
                        'style' => 'width: 100% !important;',
                        'id' => 'checkboxMulti_data[]')) !!}
                        </td>
                        <td>
                            {!! Form::text('checkboxMulti_value[]', $select[1],
                            array('placeholder' => 'Texto',
                            'class' => 'form-control input-xlarge',
                            'style' => 'width: 100% !important;',
                            'id' => 'checkboxMulti_value[]')) !!}
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm" data-content="Borrar" data-placement="left" data-toggle="popover">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>

    </div>
</div>

