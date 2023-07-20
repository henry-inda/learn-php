<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="styleprj.css">
  
</head>
<body class="dashbody">
  <?php include 'navprj.php'; ?>

  <div class="content">
    <div class="container dashboard-container">
      <div class="row">
        <div class="col-md-12">
          <div class="dashboard-item" onclick="toggleForm('income')">
            <h2>Income Capture</h2>
            <p>Click to Open Form</p>
            <form id="income-form" class="summary-form"  method="post" action="income_add.php">
              <!-- Form fields for income summary -->
              <div class="form-group">
                <label for="income-id">ID:</label>
                <input type="text" id="income-id" name="income_id" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="income-source">Source:</label>
                <input type="text" id="income-source" name="income_source" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="income-amount">Amount:</label>
                <input type="number" id="income-amount" name="income_amount" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="income-date">Date:</label>
                <input type="date" id="income-date" name="income_date" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="dashboard-item" onclick="toggleForm('expenditure')">
            <h2>Expenditure Capture</h2>
            <p>Click to Open Form</p>
            <form id="expenditure-form" class="summary-form">
              <!-- Form fields for expenditure summary -->
              <div class="form-group">
                <label for="expenditure-id">ID:</label>
                <input type="text" id="expenditure-id" name="expenditure-id" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="expenditure-date">Date:</label>
                <input type="date" id="expenditure-date" name="expenditure-date" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="expenditure-particulars">Particulars:</label>
                <input type="text" id="expenditure-particulars" name="expenditure-particulars" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="expenditure-amount">Amount spent:</label>
                <input type="number" id="expenditure-amount" name="expenditure-amount" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="expenditure-category">Category:</label>
                <input type="text" id="expenditure-category" name="expenditure-category" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="dashboard-item" onclick="toggleForm('budget')">
            <h2>Budget Capture</h2>
            <p>Click to Open Form</p>
            <form id="budget-form" class="summary-form">
              <!-- Form fields for budget summary -->
              <div class="form-group">
                <label for="budget-category">Category:</label>
                <input type="text" id="budget-category" name="budget-category" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="budget-amount">Amount:</label>
                <input type="number" id="budget-amount" name="budget-amount" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="menu-icon" onclick="toggleSidebar()">
    <span>&#9776;</span>
  </div>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="menuicon.js"></script>
</body>
</html>
