<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION["studentID"])) {
        // Redirect to the login page if not logged in
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/dtr.css">
    <script src="../js/dtr.js"></script>
</head>
<body>
<main>
    <!-- burger -->
    <img src="../assets/burger-bar.png" alt="menu" class="burger-icon">
    <!-- nav -->
    <aside class="nav">
        <a href="../time.php" class="anchor">Home</a>
        <a href="dtr.php" class="anchor">DTR</a>
        <a href="../process/logoutProcess.php" class="anchor">Logout</a>
    </aside>
    <!-- dtr container -->
    <section class="dtr">
        <div class="parent-div">
            <CAPTION>
                <div class="emphasize">
                    <p class="civil_service_title">Civil Service Form No. 48</p>
                    <p class="dtr-p">DAILY TIME RECORD </p>
                    <p class="circles">-----o0o-----</p>
                    <input id="name" value="<?=htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8');?>" readonly>
                    <p class="name">(Name)</p>
                    <div class="p-wrapper">
                        <p class="p-1">
                            For month of 
                        </p>
                        <select id="month">
                            <option value="January" >January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                        <!-- <input type="text"  value="January"> -->
                        <span class="year">20</span>
                        <input type="text" id="yearExtend" value="24" readonly>
                    </div>
                    <!-- official  -->
                    <div class="official-hour-arrival-wrapper">
                        <div class="span-wrapper">
                            <span class="span-detail">Official hours for arrival and departure</span>
                        </div>
                        <div class="reg-and-saturdays-wrapper">
                            <div class="span-input-regular-days-wrapper">
                                <span class="span-reg-days">Regular days</span>
                                <input type="text" class="days-input reg-days-input">
                            </div>
                            <div class="span-input-saturdays-wrapper">
                                <span class="span-saturdays">Saturdays</span>
                                <input type="text" class=" days-input saturdays-input">
                            </div>
                        </div>
                    </div>
                </div>
            </CAPTION>
            <!-- new Table -->
            <table class="table-dtr">
                <tbody class="tbody"> 
                    <!-- tr header -->
                    <tr class="tr-bold">
                        <td class="td"  rowspan="2">Day</td>
                        <td class="td" colspan="2">A.M</td>
                        <td class="td" colspan="2">P.M</td>
                        <td class="td" colspan="2">Undertime</td>
                    </tr>
                    <tr class="tr-bold">
                        <td class="td">Arrival</td>
                        <td class="td">Departure</td>
                        <td class="td">Arrival</td>
                        <td class="td">Departure</td>
                        <td class="td">Hours</td>
                        <td class="td">Minutes</td>
                    </tr>
                    <!-- the days here -->
                </tbody>
            </table>
            <!-- hr -->
            <div class="hr"></div>
            <!-- total hours -->
            <div class="total-h4-wrapper">
                <h4 class="total-hours">TOTAL</h3>
                <input type="text" class="input-total-hours" readonly id="inputTotalHours">
            </div>
            <!-- footer table -->
            <div class="hr"></div>
            <div class="dtr-footer">
                <p class="p-certify">I certify on my honor that the above is a true and correct report of the
                hours of work performed, record of which was made daily at the time
                of arrival and departure from office. </p>
                <input type="text" class="input-verified" readonly>
                <div class="hr" style="margin-top: 0.5em;"></div>
                <div class="input-span-incharged-wrapper">
                    <input type="text" class="input-incharge" readonly id="hteAdviser">
                    <span class="span-incharge">In-Charge</span>
                </div>
            </div>
        </div>
    </section>
</main>
</body>
</html>