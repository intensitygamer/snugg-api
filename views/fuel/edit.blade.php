@php
    // refactor magic numbers
    $permission = (new \Permission);
    $dateHelper = new \App\Helpers\DateHelper;
    $equipment_list = \App\Helpers\InputHelper::equipment_list();
    $operator_list = \App\Helpers\InputHelper::operator_list();

    $project_list = \App\Helpers\InputHelper::project_list();

@endphp

{{ Form::open(['class'=>'fuel-edit-form form form-horizontal']) }}
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                {{ Form::bsInput('text', 'transaction_no', $fuel['transaction_no'], 'Transaction ID', ['readonly']) }}
            </div>
            <div class="col-md-6">
                {{ Form::bsDatePicker('transaction_date', $dateHelper->human_date($fuel['transaction_date'], true), 'Date') }}
            </div>
        </div>
        @if($fuel['type'] == 'use')
            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsInput('text', 'no_of_hours', $fuel['no_of_hours'], 'No of Hours') }}
                </div>
                <div class="col-md-6">
                    {{ Form::bsInput('text', 'millage', $fuel['millage'], 'Millage') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsSelect('equipment', $fuel['equipment_id'], 'Equipment', $equipment_list) }}
                </div>
                <div class="col-md-6">
                    {{ Form::bsSelect('operator', $fuel['operator_id'], 'Operator', $operator_list) }}
                </div>
            </div>
            <div class="row">
         
                <div class="col-md-6">
                    {{ Form::bsSelect('project', $fuel['project_id'], 'Project', $project_list) }}
                </div>

            </div>

            {{ Form::bsTextarea('remarks', $fuel['remarks'], 'Remarks', ['rows'=>5]) }}
            {{ Form::bsTimePicker('transaction_time', $dateHelper->transaction_time($fuel['transaction_time']), 'Time of Transaction') }}
            {{ Form::bsInput('text', 'out', $fuel['out'], 'Fuel Consume') }}
        @endif
        
        @if($fuel['type'] == 'stock')

            {{ Form::bsInput('text', 'in', $fuel['in'], 'Fuel to Stock') }}


            {{ Form::bsSelect('project', $fuel['project_id'], 'Project', $project_list) }}

            {{ Form::bsInput('text', 'vendor', $fuel['vendor'], 'Vendor') }}
 
            {{ Form::bsInput('text', 'reference_no', $fuel['reference_no'], 'Reference No') }}

        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="pull-left"><span class="tmx"></span></div>
        <div class="pull-right">
            <button type="submit" class="btn btn-success text-uppercase">Update <i class="fa fa-paper-plane"></i></button>
        </div>
    </div>
</div>

{{ Form::close() }}

<script>
    CodeJquery(function () {

        $('.fuel-edit-form').submit(function (event) {
            event.preventDefault();

            sendAjax('axios', {
                url: '{{ url('fuel/edit/'.$fuel['id'].'/'.$fuel['type']) }}',
                type: 'post',
                data: $(this).serialize(),
                element: $(this)
            });

        });

        createDatePicker('dateInline', { tag: '#transaction_date', format: 'MMMM DD, YYYY', default_date: '{{ Carbon\Carbon::now()  }}' });
        createDatePicker('timeInline', { tag: '#transaction_time', format: 'LT' });

    });
</script>