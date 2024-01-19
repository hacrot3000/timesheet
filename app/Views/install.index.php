<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Login Page </title>
    <style>
        Body {
            font-family: Calibri, Helvetica, sans-serif;

        }

        button {
            background-color: #4CAF50;
            width: 100%;
            color: orange;
            padding: 15px;
            margin: 10px 0px;
            border: none;
            cursor: pointer;
        }

        form {
            border: 3px solid #f1f1f1;
        }

        input[type=text],
        input[type=password] {
            width: 100%;
            margin: 8px 0;
            padding: 12px 20px;
            display: inline-block;
            border: 2px solid green;
            box-sizing: border-box;
        }

        button:hover {
            opacity: 0.7;
        }

        .cancelbtn {
            width: auto;
            padding: 10px 18px;
            margin: 10px 5px;
        }


        .container {
            padding: 25px;
            background-color: lightblue;
        }

        .mainview
        {
            float: none;
            margin: 0 auto;
            width: 500px;
        }
    </style>
</head>

<body>
    <div class="mainview">
        <center>
            <h1> Upload database </h1>
        </center>
        <form action="{site_url}/install/upload" method="post" enctype="multipart/form-data">
            <div class="container">
                <label for="file">Select data file to upload : </label>
                <input id="file" type="file" name="fileToUpload" id="fileToUpload" required><br /><br />
                <label>Enter root password : </label>
                <input type="password" placeholder="Enter Password" name="password">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</body>

</html>