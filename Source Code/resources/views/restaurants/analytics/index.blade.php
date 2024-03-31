@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


    <div class="container-fluid">

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">

                <div class="row">
                    <div class="col-md-4">
                        <h2>Average Items Ordered (per customer)</h2>
                        <div class="form-group">
                            <select class="form-control" name="average_items" class="average_items"
                                onchange="changeDataAverageItems(this)">
                                <option value="averageorder7_day_chart">Last 7 days</option>
                                <option value="averageorder30_day_chart">Last 30 days</option>
                                <option value="averageorder12_month_chart">Last 12 months</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="average_items" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">

                <div class="row">
                    <div class="col-md-4">
                        <h2>Average Bill</h2>
                        <div class="form-group">
                            <select class="form-control" name="averagbill" class="averagbill"
                                onchange="changeDataAverageBill(this)">
                                <option value="averagbill_day_chart">Last 7 days</option>
                                <option value="averagbill30_day_chart ">Last 30 days</option>
                                <option value="averagbill12_month_chart">Last 12 months</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="averagbill" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">

                <div class="row">
                    <div class="col-md-4">
                        <h2>Average Order</h2>
                        <div class="form-group">
                            <select class="form-control" name="averagbill" class="averagbill"
                                onchange="changeDataAverageOrder(this)">
                                <option value="order_per_customer7day_chart">Last 7 days</option>
                                <option value="order_per_customer30day_chart">Last 30 days</option>
                                <option value="order_per_customer12month_chart">Last 12 months</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="average_order" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">

                <div class="row">
                    <div class="col-md-4">
                        <h2>Average Customer</h2>
                        <div class="form-group">
                            <select class="form-control" name="averagcustomer" class="averagbill"
                                onchange="changeDataAverageCustomer(this)">
                                <option value="customer_per7_day_chart">Last 7 days</option>
                                <option value="customer_per30_day_chart">Last 30 days</option>
                                <option value="customer_per12_month_chart">Last 12 months</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="averagcustomer" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

        </div>

    </div>


@endsection

@section('custom_scripts')
    <!-- Charting library -->
    <script src="https://unpkg.com/chart.js@2.9.3/dist/Chart.min.js"></script>
    <!-- Chartisan -->
    <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script>

    <!-- Your application script -->
    <script>
        // Average Bill
        const averagbill = new Chartisan({
            el: '#averagbill',
            url: "@chart('averagbill_day_chart')",
            hooks: new ChartisanHooks()
                .colors(['#000000', '#d30000'])
                .datasets([{
                    type: 'line',
                    fill: false
                }, {
                    type: 'bar',
                    fill: true
                }])
        });

        function changeDataAverageBill(value) {
            averagbill.update({
                url: `/api/chart/${value.value}`,
            })
        }

        // Average Items
        const average_items = new Chartisan({
            el: '#average_items',
            url: "@chart('averageorder7_day_chart')" + "?store_id={{ auth()->user()->id }}",
            hooks: new ChartisanHooks()
                .colors(['#000000', '#d30000'])
                .datasets([{
                    type: 'line',
                    fill: false
                }, {
                    type: 'bar',
                    fill: true
                }, ])
        });

        function changeDataAverageItems(value) {
            average_items.update({
                url: `/api/chart/${value.value}` + "?store_id={{ auth()->user()->id }}",
            })
        }

        // Average Orders
        const average_order = new Chartisan({
            el: '#average_order',
            url: "@chart('order_per_customer7day_chart')" + "?store_id={{ auth()->user()->id }}",
            hooks: new ChartisanHooks()
                .colors(['#000000', '#d30000'])
                .datasets([{
                    type: 'line',
                    fill: false
                }, {
                    type: 'bar',
                    fill: true
                }, ])
        });

        function changeDataAverageOrder(value) {
            average_order.update({
                url: `/api/chart/${value.value}` + "?store_id={{ auth()->user()->id }}",
            })
        }

        // Average Customers
        const averagcustomer = new Chartisan({
            el: '#averagcustomer',
            url: "@chart('customer_per7_day_chart')" + "?store_id={{ auth()->user()->id }}",
            hooks: new ChartisanHooks()
                .colors(['#000000', '#d30000'])
                .datasets([{
                    type: 'line',
                    fill: false
                }, {
                    type: 'bar',
                    fill: true
                }, ])
        });

        function changeDataAverageCustomer(value) {
            averagcustomer.update({
                url: `/api/chart/${value.value}` + "?store_id={{ auth()->user()->id }}",
            })
        }

    </script>
@endsection
