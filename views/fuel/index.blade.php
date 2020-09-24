@extends('templates.dmonitoring.master')
@section('title', 'Fuel Monitoring')

@php


    // refactor magic numbers
    $permission = (new \Permission);
    $dateHelper = new \App\Helpers\DateHelper;

    $monitorHelper = new \App\Helpers\MonitorHelper;
    
    $equipment_list = \App\Helpers\InputHelper::equipment_list();
    $location_list = \App\Helpers\InputHelper::location_list();
    $operator_list = \App\Helpers\InputHelper::operator_list();
    $project_list = \App\Helpers\InputHelper::project_list();

    $total_fuel_stock   = 0;
    $total_fuel_use     = 0;
    
    $backtrack_total_fuel_consumed = 0;
    $backtrack_total_fuel_stock = 0;
    
    $current_balance = 0;


    $month_list = $dateHelper->month_list();
    $year_list  = $dateHelper->years();

    if(isset($_GET['date_from']) && !empty($_GET['date_from'])) {

        $date_from = date("Y-m-d", strtotime($_GET['date_from']));

        $project = $_GET['project'];
     
        //$current_balance = $monitorHelper->get_total_fuel_previous_balance();

        $prev_month_firstDay_date = date("Y-m-d", strtotime("first day of ", strtotime("-1 month", strtotime($date_from))));

        $prev_month_lastDay_date = date("Y-m-d", strtotime("last day of ", strtotime("-1 month", strtotime($date_from))));

        $prev_month_date = date("Y-m-d", strtotime($_GET['date_from']));
 
        echo $prev_month_date;

        //$prev_month_fuel_balance_info = $monitorHelper->get_all_previous_fuel_trans($date_from, $project);

        //$total_fuel_in = $prev_month_fuel_balance_info->total_fuel_in;
        
        //$total_fuel_out = $prev_month_fuel_balance_info->total_fuel_out;

        //$total_fuel_consumed = $monitorHelper->previous_fuel_trans;


        
     }else {

        $current_balance = 0;

    }


@endphp

@section('main')
    <div class="main-container-wrapper">
        @include('templates.dmonitoring.includes.page-title', ['page_title' => 'Fuel Monitoring'])
        <div class="main-container">
            <div class="row no-margin margin-bottom-20">
                <div class="col-md-12 filter-wrapper">
                    <div class="col-md-6">
                        {!! Form::open(['method'=>'get', 'class' => 'form-horizontal', 'name' => 'fuelfilter-form']) !!}
                        <div class="row">
                            <div class="col-md-6">
                                {{ Form::bsInput('text', 'transaction', request('transaction'), 'Transaction', ['placeholder' => 'Transaction ID']) }}


                                {{ Form::bsSelect('project', request('project'), 'Projects', $project_list, ['placeholder' => '']) }}

 
                               <!--  {{ Form::bsSelect('location', request('location'), 'Location', $location_list, ['placeholder' => 'All Location',  'class'=> 'form-control location-hidden-filter' ] ) }} -->

<!--                                 {{ Form::bsSelect('operator', request('operator'), 'Operator', $operator_list, ['placeholder' => 'All Operator' ]) }}
 -->                            </div>
                            <div class="col-md-6 no-padding">


                                {{ Form::bsSelect('months', request('months'), 'Months', $month_list, ['placeholder' => '']) }}

                                {{ Form::bsSelect('year', request('year'), 'Year', $year_list, ['placeholder' => '']) }}
 
<!--                                 {{ Form::bsSelect('equipment', request('equipment'), 'Equipment', $equipment_list, ['placeholder' => 'All Equipment']) }}
 -->                                {{ Form::bsSelect('inout', request('inout'), 'IN/OUT', ['in'=>'IN','out'=>'OUT'], ['placeholder' => 'All IN/OUT']) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ url('fuel') }}" class="btn btn-warning margin-bottom-10">Reset</a>
                                {{ Form::submit('Filter', ['class' => 'btn btn-primary btn-filter display-block margin-bottom-10']) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="button" data-url="{{ url('fuel/use') }}" data-title="Use Fuel" onclick="CreateModal(this, '#use-fuel', 'appendViewModal'); return false;" class="btn btn-success"><i class="fa fa-leaf"></i> Use Fuel</button>
                                <button type="button" data-url="{{ url('fuel/stock') }}" data-title="Stock Fuel" onclick="CreateModal(this, '#stock-fuel', 'appendViewModal'); return false;" class="btn btn-danger"><i class="fa fa-hourglass-2"></i> Stock Fuel</button>

                                <a href="#" data-url="{{ url('manage/pe/fuel/print') }}" data-title="Print Date Selection" onclick="CreateModal(this, '#print-modal', 'appendViewModal'); return false;" class="btn btn-warning">
                                    <!-- <i class="fa fa-file-excel-o"></i> EXPORT | --> <i class="fa fa-print"></i> PRINT</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="table-responsive margin-bottom-20">
                <table class="table table-striped table-bordered table-hover table-ecms">
                    <thead>
                        <tr class="top-header">
                            <th colspan="11">Transaction Information</th>
                            <th colspan="6">Consumption</th>
                        </tr>
                        <tr class="default-header">
                            <th rowspan="2">Transaction ID</th>
                            <th rowspan="2">Date</th>
                            <th rowspan="2">Time of <br />Transaction</th>
                            <th rowspan="2">Vendor</th>
                            <th rowspan="2">Reference No.</th>
                            <th rowspan="2">Equipment</th>
                            <th rowspan="2">No. of Hours</th>
                            <th rowspan="2">Millage</th>
                            <th rowspan="2">Location</th>
                            <th rowspan="2">Operator</th>
                            <th rowspan="2">Project</th>
                            <th colspan="2">Qty in Liters</th>
                            <th rowspan="2">Total Consumption <br />Per Project</th>
                            <th rowspan="2">Remaining Balance</th>
                            <th rowspan="2">Remarks</th>
                            <th rowspan="2"></th>
                        </tr>
                        <tr class="bottom-header">
                            <th>IN</th>
                            <th>OUT</th>
                        </tr>
                    </thead>
                    <tbody>
                

                    @if(count($fuels) < 1)
                        <tr>
                            <td colspan="26" class="table-no-records">- No records -</td>
                        </tr>
                    @endif
                    @if($filter)

                        @php 
                            
                             $total_fuel_consume = 0; 

                        @endphp

                        @foreach ($fuels->items() as $fuel)


                        @php

                            $current_balance += $fuel['in'];
                                    
                            $current_balance -= $fuel['out'];
                            
                            $total_fuel_consume += $fuel['out'];

                        @endphp
                            <tr>
                                <td>{{ $fuel['transaction_no'] }}</td>
                                <td>{{ $dateHelper->transaction_date($fuel['transaction_date']) }}</td>
                                <td>{{ $dateHelper->transaction_time($fuel['transaction_time']) }}</td>
                                <td>{{ $fuel['vendor'] }}</td>
                                <td>{{ $fuel['reference_no'] }}</td>
                                <td>{{ $fuel['equipment'] }}</td>
                                <td>{{ $fuel['no_of_hours'] }}</td>
                                <td>{{ $fuel['millage'] }}</td>
                                <td>{{ $fuel['location'] }}</td>
                                <td>{{ $fuel['operator'] }}</td>
                                <td>{{ $fuel['project'] }}</td>
                                <td class="alert alert-success" align=center>{{ $fuel['in'] }}</td>
                                <td class="alert alert-success" align=center>{{ $fuel['out'] }}</td>
                                <td class="alert alert-warning" align=center>{{ $total_fuel_consume }}</td>
                                <td class="alert alert-info" align=center>{{ $current_balance  }}
                                <td>{{ $fuel['remarks'] }}</td>
                                <td>
                                    <a href="#" data-url="{{ url('fuel/edit/'.$fuel['id'].'/'.$fuel['type']) }}" data-title="Edit - {{ $fuel['transaction_no'] }}" onclick="CreateModal(this, '#edit-lubricant', 'appendViewModal'); return false;"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr>

                            @php
                                $total_fuel_stock = bcadd($total_fuel_stock, $fuel['in'], 3);
                                $total_fuel_use = bcadd($total_fuel_use, $fuel['out'], 3);

                            @endphp

                        @endforeach
                    @else
                        <tr>
                            <td colspan="26" class="table-no-records text-center">- Please use filter for better result. -</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                        @if($filter  && count($fuels))
 
                        <tr class="total-stock-filter">
                            <td colspan="3" class="text-right">Previous Month Total fuel stock:</td>
                            <td colspan="3">{{ $total_fuel_stock }}</td>

                            <td> Previous Month Balance</td>
                            <td>{{ $total_fuel_consume }}</td>
                            
                            <td colspan="8" class="text-right">Total fuel stock:</td>
                            <td>{{ $backtrack_total_fuel_stock }}</td>

                        </tr>

                        <tr class="total-consumption-filter">
                            
                            <td colspan="3" class="text-right">Previous Month Total fuel consumed:</td>

                            <td colspan="3">{{ $backtrack_total_fuel_consumed }}</td>

                            <td colspan="10" class="text-right">Total fuel consume:</td>
                            <td colspan="3">{{ $total_fuel_consume }}</td>



                        </tr>

                    </tfoot>

                    @endif
                </table>
            </div>
            {{  $fuels->setPath(request()->getPathInfo())->appends(\Request::all())->render() }}

        </div>
    </div>
    
    <style>
        #app-vue > div.container-fluid > div > div > div.main-container > div.row.no-margin.margin-bottom-20 > div > div:nth-child(1) > form > div:nth-child(1) > div:nth-child(1) > div:nth-child(3) > .col-md-12,
        #app-vue > div.container-fluid > div > div > div.main-container > div.row.no-margin.margin-bottom-20 > div > div:nth-child(1) > form > div:nth-child(1) > div:nth-child(1) > div:nth-child(3){
            height:0px;
            margin:0px;
            padding:0;
        }

        ul#searched-location{
            position: absolute;
            z-index: 9;
            background: white;
            list-style: none;
            padding: 10!important;
            border: 1px solid green;
            width: 90%;
        }

        ul#searched-location li{
            cursor: pointer;
            padding: 2px;
        }
        ul#searched-location li:hover{
            background-color: green;
            color:#fafafa;
        }

        }
        #location-filtered-field{
            height:0px;
            margin:0px;
            padding:0;
        }
    </style>

    @push('scripts')
        <script>
            CodeJquery(function(){
                function onLoadLocationField(){
                    var url_string = window.location.href;
                    var url = new URL(url_string);
                    var locationID= url.searchParams.get("location");
                    console.log(locationID);
                     axios.get('/location/search/by?id='+locationID )

                        .then(function (response) {
                            console.log(response.data.name);
                            var locationName = response.data.name
                            $("#location-search-field").val(locationName);
                        });
                }

                onLoadLocationField();

                $('#location-search-field').keyup(function(){
                    
                    var val = $(this).val();
                    if(val !== ''){
                        axios.get('/location/search?query='+val )

                        .then(function (response) {

                            
                            var locationList = '';
                            var locationFilter = '#app-vue > div.container-fluid > div > div > div.main-container > div.row.no-margin.margin-bottom-20 > div > div:nth-child(1) > form > div:nth-child(1) > div:nth-child(1) > div:nth-child(2) > div > div';
                            $('#searched-location').remove();

                            for(var i =0;i<response.data.length;i++){
                                locationList +='<li class="li-location-selection" data-id="'+response.data[i].id+'">'+response.data[i].name+'</li>';
                            }

                            //console.log(locationList);

                            var ul = '<ul id="searched-location" class="list-group">'+locationList+'</ul>';

                            console.log(ul);
                            $('#app-vue > div.container-fluid > div > div > div.main-container > div.row.no-margin.margin-bottom-20 > div > div:nth-child(1) > form > div:nth-child(1) > div:nth-child(1) > div:nth-child(2) > div > div').append(ul);
                            //CreateNoty({type:response.data.status, text: response.data.message})
                        })
                        .catch(function (error) {

                            CreateNoty({type:'error', text: error.response.data.message})
                        });
                    }else{
                        $("#location-filtered-field-hidden").val('');
                    }

                });

                $(document).on("click","#searched-location > li",function(){
                    var id = $(this).attr("data-id");
                    var locationName = $(this).html();
                    var locationID = $("#location-filtered-field-hidden");
                    console.log(id,'location id');
                    $("#location-search-field").val(locationName);
                    locationID.val(id);
                    $("#searched-location").remove();
                });
            });
        </script>
    @endpush
@endsection