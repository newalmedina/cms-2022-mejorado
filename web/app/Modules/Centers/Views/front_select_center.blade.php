{!! Form::open(array('role' => 'form', "id"=>"seleccion_centro")) !!}
    <div class="form-group">
        {!! Form::select('center_id',  $centros, auth()->user()->userProfile->center_id, ['class' => 'form-control', 'id' => 'center_id', 'required' => true]) !!}
    </div><!-- form-group -->
{!! Form::close() !!}

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>


{!! JsValidator::formRequest('App\Modules\Centers\Requests\CentersSelectorRequest')->selector('#seleccion_centro') !!}
