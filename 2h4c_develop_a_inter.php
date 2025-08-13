<?php
// Interactive Web App Simulator Notebook

// Set up a PHP session to store user input and app state
session_start();

// Define a function to update the app state based on user input
function updateAppState($input) {
  // Get the current app state from the session
  $appState = $_SESSION['app_state'];

  // Update the app state based on the user input
  switch ($input) {
    case 'login':
      $appState['login_status'] = true;
      break;
    case 'logout':
      $appState['login_status'] = false;
      break;
    case 'add_todo':
      array_push($appState['todo_list'], $_POST['todo_item']);
      break;
    case 'delete_todo':
      unset($appState['todo_list'][$_POST['todo_index']]);
      $appState['todo_list'] = array_values($appState['todo_list']);
      break;
    default:
      // Handle other inputs or errors
  }

  // Update the session with the new app state
  $_SESSION['app_state'] = $appState;
}

// Define the initial app state
$appState = array(
  'login_status' => false,
  'todo_list' => array()
);
$_SESSION['app_state'] = $appState;

// Process user input if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  updateAppState($_POST['action']);
}

// Display the interactive web app simulator
?>
<html>
  <head>
    <title>Interactive Web App Simulator</title>
    <style>
      body {
        font-family: Arial, sans-serif;
      }
    </style>
  </head>
  <body>
    <?php if (!$_SESSION['app_state']['login_status']) { ?>
      <h1>Welcome!</h1>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" name="action" value="login">
      </form>
    <?php } else { ?>
      <h1>Todo List App</h1>
      <ul>
        <?php foreach ($_SESSION['app_state']['todo_list'] as $index => $todoItem) { ?>
          <li>
            <?php echo $todoItem; ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <input type="hidden" name="action" value="delete_todo">
              <input type="hidden" name="todo_index" value="<?php echo $index; ?>">
              <input type="submit" value="Delete">
            </form>
          </li>
        <?php } ?>
      </ul>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="todo_item" placeholder="Add Todo Item">
        <input type="hidden" name="action" value="add_todo">
        <input type="submit" value="Add">
      </form>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="action" value="logout">
        <input type="submit" value="Logout">
      </form>
    <?php } ?>
  </body>
</html>