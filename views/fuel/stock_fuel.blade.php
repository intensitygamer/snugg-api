@php

    // refactor magic numbers
    $permission = (new \Permission);
    $location_list = \App\Helpers\InputHelper::location_list();

    $project_list = \App\Helpers\InputHelper::project_list();

    

@endphp

{{ Form::open(['class'=>'fuel-stock-form form form-horizontal']) }}
    <div class="row">
        <div class="col-md-12">

            {{ Form::bsInput('text', 'transaction_no', $transaction, 'Transaction ID', ['readonly']) }}
           
            {{ Form::bsDatePicker('transaction_date', old('transaction_date'), 'Date') }}

            {{ Form::bsInput('text', 'in', old('in'), 'Fuel to Stock') }}
            {{ Form::bsInput('text', 'vendor', old('vendor'), 'Vendor') }}

            {{ Form::bsSelect('project', old('project'), 'Project', $project_list) }}

            {{ Form::bsInput('text', 'reference_no', old('reference_no'), 'Reference No') }}
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <button type="submit" class="btn btn-success text-uppercase">Submit <i class="fa fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
{{ Form::close() }}

<script>
    CodeJquery(function () {

        $('.fuel-stock-form').submit(function (event) {
            event.preventDefault();

            sendAjax('axios', {
                url: '{{ url('fuel/store/stock') }}',
                type: 'post',
                data: $(this).serialize(),
                element: $(this)
            });

        });

        createDatePicker('dateInline', { tag: '#transaction_date', format: 'MMMM DD, YYYY', default_date: '{{ Carbon\Carbon::now()  }}' });

    });
</script>