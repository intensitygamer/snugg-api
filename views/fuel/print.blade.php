@extends('templates.dmonitoring.print')
@section('title', 'Fuel Monitoring')

@php
    // refactor magic numbers
    $permission = (new \Permission);
    $dateHelper = new \App\Helpers\DateHelper;
    $monitorHelper = new \App\Helpers\MonitorHelper;
    $equipment_list = \App\Helpers\InputHelper::equipment_list();
    $location_list = \App\Helpers\InputHelper::location_list();
    $operator_list = \App\Helpers\InputHelper::operator_list();
    $total_fuel_stock = 0;
    $total_fuel_use = 0;
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/print-page.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/print.css') }}" type="text/css" media="print" />
@endpush

@section('main')
    <div class="main-container-wrapper">
        <div class="main-container">

            @php 
                $inner_divider = 15;
                $row_count_top = 0;
                $fuels_count = count($fuels);
                $remainder = $fuels_count % 15;
                $pager_count = 1;
                $pages = $fuels_count / 15;
                $total_pages = ceil($pages);
                $current_page = 1;
                $fuel_array = $fuels;
                
                $divider = 15;
                
                $inner_divider = 0;
                $outer_divider = 15;
                $displayed_fuel = 0;

                $start_display = 0;
                $end_display = 15;

                $count_out = 1;

            @endphp
            
            @if($fuels_count <= $divider)

                <div class="table-responsive margin-bottom-20">
                    <table class="table table-striped table-bordered table-hover table-ecms">
                                <thead>
                                    
<!--                                     <th>PROJECT:</th>
                                    <th colspan="2"><?php echo Request::get('project'); ?></th> -->
                                    <th colspan="17" rowspan="3" class="vertical-align-middle">
                                        <div class="wraptable-image">
                                            <img src="{{ asset('img/dakay-logo.png') }}" class="pull-left" />
                                            <span class="align-text-in-image"> </span>
                                        </div>
                                    </th>

                                    <tr>
                                        
                                    </tr>
                                    <tr>
                                        
                                    </tr>
                                    <tr>
                                        
                                    </tr>

                                    <th rowspan="4" class="vertical-align-top">FROM <br />
                                        @if(!empty(Request::get('date_from')))
                                            {{ $dateHelper->transaction_date(Request::get('date_from')) }}
                                        @endif
                                    </th>

                                    <th rowspan="4" colspan="12" class="vertical-align-top">TO <br />
                                        @if(!empty(Request::get('date_to')))
                                            {{ $dateHelper->transaction_date(Request::get('date_to')) }}
                                        @endif
                                    </th>

                                    <td colspan="4">

                                 </tr>
                                <tr> 
                                <tr> 
                                <tr> 
                                <tr> 

                                    <tr class="top-header">
                                        <th colspan="14">Transaction Information</th>
                                        <th colspan="4">Consumption</th>
                                    </tr>
                                    <tr class="default-header">
                                        <!-- <th rowspan="2">Transaction ID</th> -->
                                        <th rowspan="1">Date</th>
                                        <th rowspan="1">Time of <br />Transaction</th>
                                        <th rowspan="1">Vendor</th>
                                        <th rowspan="1">Reference No.</th>
                                        <th rowspan="1">Equipment</th>
                                        <th rowspan="1">No. of Hours</th>
                                        <th rowspan="1">Millage</th>
                                        <th rowspan="1">Location</th>
                                        <th rowspan="1">Operator</th>
                                        <th rowspan="1">Project</th>
                                        <th colspan="1">Qty in Liters</th>
                                        <th rowspan="1">Total Consumption <br />Per Unit</th>

                                        <th>IN</th>
                                        <th>OUT</th>
                                        <th rowspan="1">Remaining Balance</th>
                                        <th rowspan="1">Remarks</th>


                                     </tr>

                                </thead>

                                <tbody>

                                @if(count($fuels) < 1)
                                    <tr>
                                        <td colspan="26" class="table-no-records">- No records -</td>
                                    </tr>
                                @endif

                                @foreach ($fuels as $fuel)
                                        <tr> 
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
                                            <td>{{ $fuel['in'] }}</td>
                                            <td>{{ $fuel['out'] }}</td>
                                            <td>{{ $fuel['total_consumption_per_unit'] }}</td>
                                            <td>{{ $fuel['balance'] }}</td>
                                            <td>{{ $fuel['remarks'] }}</td>
                                            <td></td>
                                        </tr>
                                        @php
                                            $total_fuel_stock = bcadd($total_fuel_stock, $fuel['in'], 3);
                                            $total_fuel_use = bcadd($total_fuel_use, $fuel['out'], 3);
                                        @endphp
                                @endforeach
                                

                                </tbody>

                                <tfoot>
                                    <tr class="total-stock-filter">
                                        <td colspan="14" class="text-right">Total fuel stock:</td>
                                        <td colspan="3">{{ $total_fuel_stock }}</td>
                                    </tr>
                                    <tr class="total-consumption-filter">
                                        <td colspan="14" class="text-right">Total fuel consume:</td>
                                        <td colspan="3">{{ $total_fuel_use }}</td>
                                    </tr>
                                </tfoot>
                                
                            </table>
                        </div>

                        <!-- Page Of Total Pages -->
                        <div class="text-center">
                            Page {{ $current_page }} of {{ $total_pages }}    
                        </div>

            @else

                @for ($i = 1; $i <= $fuels_count; $i++)
                    @if($i % $divider === 0)
                        <div class="table-responsive margin-bottom-20">
                            <table class="table table-striped table-bordered table-hover table-ecms">
                                <thead>

                                <tr> 
                                    <th colspan="6" rowspan="3" class="vertical-align-middle">
                                        <div class="wraptable-image">
                                            <img src="{{ asset('img/dakay-logo.png') }}" class="pull-left" />
                                            <span class="align-text-in-image"></span>
                                        </div>
                                    </th>

                                    <th rowspan="4" class="vertical-align-top">FROM <br />
                                        @if(!empty(Request::get('date_from')))
                                            {{ $dateHelper->transaction_date(Request::get('date_from')) }}
                                        @endif
                                    </th>

                                    <th rowspan="4" class="vertical-align-top">TO <br />
                                        @if(!empty(Request::get('date_to')))
                                            {{ $dateHelper->transaction_date(Request::get('date_to')) }}
                                        @endif
                                    </th>

                                    <td colspan="4">

                                 </tr>
                                <tr> 
                                <tr> 
                                <tr> 

                                    <tr class="top-header">
                                        <th colspan="11">Transaction Information</th>
                                        <th colspan="6">Consumption</th>
                                    </tr>

                                    <tr class="default-header">
                                        <!-- <th rowspan="2">Transaction ID</th> -->
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
                                        <th rowspan="2">Total Consumption <br />Per Unit</th>
                                        <th rowspan="2">Remaining Balance</th>
                                        <th rowspan="2">Remarks</th>
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
                                @php
                                    $count = 0;
                                    $row_count = $displayed_fuel;
                                    $ii_inner_divider = $inner_divider + 1;
                                    $ii_outer_divider = $outer_divider + 1;
                                @endphp

                                @foreach ($fuels as $fuel)
                                   @if($count >= $start_display && $count <  $end_display)
                                        <tr>
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
                                            <td>{{ $fuel['in'] }}</td>
                                            <td>{{ $fuel['out'] }}</td>
                                            <td>{{ $fuel['total_consumption_per_unit'] }}</td>
                                            <td>{{ $fuel['balance'] }}</td>
                                            <td>{{ $fuel['remarks'] }}</td>
 
                                        </tr>
                                        @php
                                            $total_fuel_stock = bcadd($total_fuel_stock, $fuel['in'], 3);
                                            $total_fuel_use = bcadd($total_fuel_use, $fuel['out'], 3);
                                            $count_out = bcadd($count_out, 1);
                                            $displayed_fuel = bcadd($displayed_fuel, 1);
                                        @endphp

                                    @endif

                                    @php
                                        $count = bcadd($count, 1);

                                        $inner_divider = bcadd($inner_divider, 1);
                                        $outer_divider = bcadd($outer_divider, 1);
                                    @endphp

                                @endforeach
                                
                                @php
                                    $start_display = bcadd($start_display, 15);
                                    $end_display = bcadd($end_display, 15 );
                                @endphp

                                </tbody>

                               
                                @if($displayed_fuel > ($fuels_count-1)   )
                                <tfoot>
                                    <tr class="total-stock-filter">
                                        <td colspan="14" class="text-right">Total fuel stock:</td>
                                        <td colspan="3">{{ $total_fuel_stock }}</td>
                                    </tr>
                                    <tr class="total-consumption-filter">
                                        <td colspan="14" class="text-right">Total fuel consume:</td>
                                        <td colspan="3">{{ $total_fuel_use }}</td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>

                        <!-- <div class="row">
                            <div class="col-md-6">
                                <div class="row footer-checker">
                                    <div class="col-md-4">
                                        <div class="title text-right">
                                            Checked By:
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="name">&nbsp;</div>
                                        <div class="position">Designated Personel</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row footer-checker">
                                    <div class="col-md-5">
                                        <div class="title text-right">
                                            Approved By:
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="name">
                                            Petrious Dakay
                                        </div>
                                         <div class="position">Project Personnel</div> 
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!-- Page Of Total Pages -->
                        <div class="text-center">
                            Page {{ $current_page }} of {{ $total_pages }}    
                        </div>
                        
                        @php
                            $pager_count  += 1;
                            $current_page += 1;
                        @endphp
                        


                    <!-- Last Page to Display -->
                    @elseif($displayed_fuel <= ($inner_divider - 1) )

                        
                        <div class="table-responsive margin-bottom-20">
                            <table class="table table-striped table-bordered table-hover table-ecms">
                                <thead>
                                    <tr class="top-header">
                                        <th colspan="11">Transaction Information</th>
                                        <th colspan="6">Consumption</th>
                                    </tr>
                                    <tr class="default-header">
                                        <!-- <th rowspan="2">Transaction ID</th> -->
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
                                        <th rowspan="2">Total Consumption <br />Per Unit</th>
                                        <th rowspan="2">Remaining Balance</th>
                                        <th rowspan="2">Remarks</th>
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
                                @php
                                    $count = 0;
                                    $row_count = $displayed_fuel;
                                    $ii_inner_divider = $inner_divider + 1;
                                    $ii_outer_divider = $outer_divider + 1;
                                @endphp

                                @foreach ($fuels as $fuel)
                                   @if($count >= $displayed_fuel  )
                                        <tr>
                                            <!-- <td>{{ $fuel['transaction_no'] }}</td> -->
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
                                            <td>{{ $fuel['in'] }}</td>
                                            <td>{{ $fuel['out'] }}</td>
                                            <td>{{ $fuel['total_consumption_per_unit'] }}</td>
                                            <td>{{ $fuel['balance'] }}</td>
                                            <td>{{ $fuel['remarks'] }}</td>
                                          </tr>
                                        @php
                                            $total_fuel_stock = bcadd($total_fuel_stock, $fuel['in'], 3);
                                            $total_fuel_use = bcadd($total_fuel_use, $fuel['out'], 3);
                                            $count_out = bcadd($count_out, 1);
                                            $displayed_fuel = bcadd($displayed_fuel, 1);
                                        @endphp

                                    @endif

                                    @php
                                        $count = bcadd($count, 1);
                                    @endphp

                                @endforeach
                                
                                @php
                                    
                                @endphp

                                </tbody>

                               
                                @if($displayed_fuel > ($fuels_count-1)   )
                                <tfoot>
                                    <tr class="total-stock-filter">
                                        <td colspan="14" class="text-right">Total fuel stock:</td>
                                        <td colspan="3">{{ $total_fuel_stock }}</td>
                                    </tr>
                                    <tr class="total-consumption-filter">
                                        <td colspan="14" class="text-right">Total fuel consume:</td>
                                        <td colspan="3">{{ $total_fuel_use }}</td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>

                        <!-- <div class="row">
                            <div class="col-md-6">
                                <div class="row footer-checker">
                                    <div class="col-md-4">
                                        <div class="title text-right">
                                            Checked By:
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="name">&nbsp;</div>
                                        <div class="position">Designated Personel</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row footer-checker">
                                    <div class="col-md-5">
                                        <div class="title text-right">
                                            Approved By:
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="name">
                                            Petrious Dakay
                                        </div>
                                        <div class="position">Project Personnel</div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    @endif 

                @endfor



                <!-- Page Of Total Pages -->
                <div class="text-center">
                    Page {{ $current_page }} of {{ $total_pages }}
                </div>
            

            @endif

            <!-- Print -->
            <div class="row">
                <div class="col-md-12">
                    <a onClick="window.print()" class="hanging-print btn hidden-print">Print</a>
                </div>
            </div>
        </div>
    </div>
@endsection