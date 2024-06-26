<?php

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');
header('X-Robots-Tag: noindex, nofollow', true);

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="HandheldFriendly" content="True" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABqklEQVQ4jZ2Tv0scURDHP7P7SGWh14mkuXJZEH8cgqUWcklAsLBbCEEJSprkD7hD/4BUISHEkMBBiivs5LhCwRQBuWgQji2vT7NeYeF7GxwLd7nl4knMwMDMfL8z876P94TMLt+8D0U0EggQSsAjwMvga8ChJAqxqjTG3m53AQTg4tXHDRH9ABj+zf6oytbEu5d78nvzcyiivx7QXBwy46XOi5z1jbM+Be+nqVfP8yzuD3FM6rzIs9YE1hqGvDf15cVunmdx7w5eYJw1pcGptC9CD4gBUuef5Ujq/BhAlTLIeFYuyfmTZgeYv+2nPt1a371P+Hm1WUPYydKf0lnePwVmh3hnlcO1uc7yvgJUDtdG8oy98kduK2KjeHI0fzCQINSXOk/vlXBUOaihAwnGWd8V5r1uhe1VIK52V6JW2D4FqHZX5lphuwEE7ooyaN7gjLMmKSwYL+pMnV+MA/6+g8RYa2Lg2RBQbj4+rll7uymLy3coiuXb5PdQVf7rKYvojAB8Lf3YUJUHfSYR3XqeLO5JXvk0dhKqSqQQoCO+s5AIxCLa2Lxc6ALcAPwS26XFskWbAAAAAElFTkSuQmCC" />

    <title>Send Signal Message</title>
    <meta name="description" content="Signal - Send Message to your Signal recipients."/>

    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css" integrity="sha512-IgmDkwzs96t4SrChW29No3NXBIBv8baW490zk5aXvhCD8vuZM3yUSkbyTBcXohkySecyzIrUwiF/qV0cuPcL3Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Signika+Negative:wght@300..700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #2e2e2e;
            color: #d4d4d4;
            font-family: "Signika Negative", sans-serif;
        }
        .card {
            font-family: "Signika Negative", sans-serif;
            background-color: #1e1e1e;
            color: #d4d4d4;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }
        .button.is-link {
            font-family: "Signika Negative", sans-serif;
            background-color: #6a1b9a;
            border-color: #6a1b9a;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .button.is-link:hover {
            background-color: #4a148c;
            border-color: #4a148c;
        }
        .button.is-danger {
            font-family: "Signika Negative", sans-serif;
            border-color: #6a1b9a;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .button.is-danger:hover {
            border-color: #4a148c;
        }
        .notification {
            font-family: "Signika Negative", sans-serif;
            background-color: #2e2e2e;
            color: #d4d4d4;
            border: 1px solid #6a1b9a;
            border-radius: 10px;
        }
        .input, .textarea {
            font-family: "Signika Negative", sans-serif;
            background-color: #2e2e2e;
            color: #d4d4d4;
            border: 1px solid #6a1b9a;
            border-radius: 10px;
        }
        .input:focus, .textarea:focus {
            border-color: #4a148c;
        }
        .loading-text {
            font-family: "Signika Negative", sans-serif;
            display: none;
            color: #d4d4d4;
        }
        .buttons {
            font-family: "Signika Negative", sans-serif;
            display: flex;
            justify-content: space-between;
        }
    </style>

</head>
<body>
    <section class="section">
        <div class="container">
            <div class="card">
                <div class="card-content">
                    <h1 class="title" style="color: #d4d4d4;">Send Message</h1>
                    <div id="notification" class="notification is-hidden"></div>
                    <form id="messageForm">
                        <div class="field">
                            <label class="label">Message</label>
                            <div class="control">
                                <textarea class="textarea" id="message" name="message" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Number</label>
                            <div class="control">
                                <input class="input" type="text" id="number" name="number" required pattern="\+\d{1,15}">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Recipients</label>
                            <div class="control">
                                <input class="input" type="text" id="recipients" name="recipients" required pattern="\+\d{1,15}(,\+\d{1,15})*">
                            </div>
                        </div>
                        <div class="field">
                            <div class="buttons">
                                <button class="button is-link" type="submit">Send</button>
                                <button class="button is-danger" type="reset">Reset</button>
                            </div>
                        </div>
                        <p class="loading-text">Sending...</p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            loadFormData();

            document.getElementById('messageForm').addEventListener('submit', async function(event) {
                event.preventDefault();

                const message = document.getElementById('message').value.trim();
                const number = document.getElementById('number').value.trim();
                const recipients = document.getElementById('recipients').value.trim().split(',');

                if (!message || !number || !recipients.length) {
                    showNotification('All fields are required', 'is-danger');
                    return;
                }

                const loadingText = document.querySelector('.loading-text');
                loadingText.style.display = 'block';

                try {
                    const response = await fetch('/api/process_signal_form.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ message, number, recipients })
                    });

                    const result = await response.json();
                    loadingText.style.display = 'none';

                    if (response.ok) {
                        showNotification(result.message || 'Message sent successfully', 'is-success');
                        saveFormData();
                    } else {
                        showNotification(result.error || 'Error sending message', 'is-danger');
                    }
                } catch (error) {
                    loadingText.style.display = 'none';
                    showNotification(`Error: ${error.message}`, 'is-danger');
                }
            });

            document.getElementById('messageForm').addEventListener('reset', () => {
                clearNotification();
                localStorage.removeItem('formData');
            });
        });

        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            notification.classList.remove('is-hidden');
            setTimeout(() => {
                notification.classList.add('is-hidden');
            }, 3000);
        }

        function clearNotification() {
            const notification = document.getElementById('notification');
            notification.classList.add('is-hidden');
            notification.textContent = '';
        }

        function saveFormData() {
            const formData = {
                message: document.getElementById('message').value,
                number: document.getElementById('number').value,
                recipients: document.getElementById('recipients').value
            };
            localStorage.setItem('formData', JSON.stringify(formData));
        }

        function loadFormData() {
            const formData = JSON.parse(localStorage.getItem('formData'));
            if (formData) {
                document.getElementById('message').value = formData.message;
                document.getElementById('number').value = formData.number;
                document.getElementById('recipients').value = formData.recipients;
            }
        }
    </script>
</body>
</html>