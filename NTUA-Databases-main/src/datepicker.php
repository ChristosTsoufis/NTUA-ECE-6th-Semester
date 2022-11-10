<link href="daterangepicker.css" rel="stylesheet">
<div class="container-fluid mt-2 mb-3">
    <label class="mb-2 px-2">Start Date - End Date</label>
    <input type="text" id="picker" class="form-control" name="daterange">
    <div class="custom-control custom-switch container-fluid mt-3">
        <input type="checkbox" class="custom-control-input" id="customSwitch1" value="1" <?php if (isset($_GET['dateswitch'])) echo "checked='checked'"; ?> name="dateswitch">
        <label class="custom-control-label" for="customSwitch1">Use Date Range Option</label>
    </div>
</div>

<script src="moment.min.js"></script>
<script src="daterangepicker.js"></script>
<script>
    var start_date = "<?php echo $startdate ?>";
    var end_date = "<?php echo $enddate ?>";
    $('#picker').daterangepicker({
        autoApply: true,
        startDate: start_date,
        endDate: end_date,
        showDropdowns: true,
        opens: 'right',
        drops: 'down',
        locale: {
            format: 'YYYY-MM-DD'
        }
    })
</script>