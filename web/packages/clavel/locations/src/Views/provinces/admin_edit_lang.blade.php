<div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-lang" role="tablist">

            <?php
            $nX = 1;
            ?>
            @foreach ($a_trans as $key => $valor)
                <li class="nav-item">
                    <a class="nav-link  @if ($nX == 1) active @endif" id="tab_{{ $key }}_tab" data-toggle="pill"
                        href="#tab_{{ $key }}" role="tab" aria-controls="tab_{{ $key }}"
                        aria-selected="true">
                        {{ $valor['idioma'] }}
                        @if($nX==1)- <span class="text-success">{{ trans('locations::provinces/admin_lang.defecto') }}</span>@endif
                    </a>
                </li>
                <?php
                $nX++;
                ?>
            @endforeach
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="custom-tabs-langContent">
            <?php
            $nX = 1;
            ?>
            @foreach ($a_trans as $key => $valor)
              <div id="tab_{{ $key }}" class="tab-pane fade show @if($nX==1) active @endif"
                    role="tabpanel"
                    aria-labelledby="tab_{{ $key }}_tab">

                    {!!  Form::hidden('lang['.$key.'][id]', $valor["id"], array('id' => 'id')) !!}

                    {{-- Text Lang - name --}}
<div class="form-group row">
    {!! Form::label('lang['.$key.'][name]', trans('locations::provinces/admin_lang.fields.name'), array('class' => 'col-sm-2 col-form-label')) !!}
    <div class="col-sm-10">
        {!! Form::text('lang['.$key.'][name]', $province->{'name:'.$key} , array('placeholder' => trans('locations::provinces/admin_lang.fields.name_helper'), 'class' => 'form-control', 'id' => 'name_'.$key)) !!}
    </div>
</div>


                </div>
                <?php
                $nX++;
                ?>
            @endforeach
        </div>
    </div>
    <!-- /.card -->
</div>
