@php
    // refactor magic numbers
    $permission = (new \Permission);
    $equipment_list = \App\Helpers\InputHelper::equipment_list();
    $location_list = \App\Helpers\InputHelper::location_list();
    $operator_list = \App\Helpers\InputHelper::operator_list();
    $project_list = \App\Helpers\InputHelper::project_list();
 
 @endphp

{{ Form::open(['class'=>'fuel-use-form form form-horizontal']) }}
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsInput('text', 'transaction_no', $transaction, 'Transaction ID', ['readonly']) }}
                </div>
                <div class="col-md-6">
                    {{ Form::bsDatePicker('transaction_date', old('transaction_date'), 'Date') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsInput('text', 'no_of_hours', old('no_of_hours'), 'No of Hours') }}
                </div>
                <div class="col-md-6">
                    {{ Form::bsInput('text', 'millage', old('millage'), 'Millage') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsSelect('equipment', old('equipment'), 'Equipment', $equipment_list) }}
                </div>
                <div class="col-md-6">
                    {{ Form::bsSelect('operator', old('operator'), 'Operator', $operator_list) }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsSelect('project', old('project'), 'Project', $project_list) }}
                 </div>
<!--                 <div class="col-md-6">
                    {{ Form::bsSelect('location', old('location'), 'Location', $location_list) }}
                </div> -->
            </div>
            {{ Form::bsTextarea('remarks', old('remarks'), 'Remarks', ['rows'=>5]) }}
            {{ Form::bsTimePicker('transaction_time', old('transaction_time'), 'Time of Transaction') }}
            {{ Form::bsInput('text', 'out', old('out'), 'Fuel Consume') }}
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

        $('.fuel-use-form').submit(function (event) {
            event.preventDefault();

            sendAjax('axios', {
                url: '{{ url('fuel/store/use') }}',
                type: 'post',
                data: $(this).serialize(),
                element: $(this)
            });

        });

        createDatePicker('dateInline', { tag: '#transaction_date', format: 'MMMM DD, YYYY', default_date: '{{ Carbon\Carbon::now()  }}' });
        createDatePicker('timeInline', { tag: '#transaction_time', format: 'LT' });

    });
</script>