document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('.needs-validation');
    for (var i = 0; i < forms.length; i++) {
        forms[i].addEventListener('submit', function (event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    }
    // Light / dark mode slider switch in the menu
    var themeButton = document.getElementById('theme-toggle');
    if (themeButton) {
        var updateSwitch = function () {
            var current = document.documentElement.getAttribute('data-bs-theme');
            themeButton.setAttribute('aria-checked', current === 'dark' ? 'true' : 'false');
        };
        updateSwitch();
        themeButton.addEventListener('click', function () {
            var current = document.documentElement.getAttribute('data-bs-theme');
            var next = 'dark';
            if (current === 'dark') {
                next = 'light';
            }
            document.documentElement.setAttribute('data-bs-theme', next);
            try {
                localStorage.setItem('theme', next);
            } catch (error) {
                // saving is not possible, the theme still changes for this page
            }
            updateSwitch();
        });
    }
    // confirm deleting a record or cancelling a registration.
    var buttons = document.querySelectorAll('[data-confirm]');
    for (var j = 0; j < buttons.length; j++) {
        buttons[j].addEventListener('click', function (event) {
            if (!window.confirm(this.getAttribute('data-confirm'))) {
                event.preventDefault();
            }
        });
    }

    // Notification bell: ask the server for the unread count every 30 seconds
    var bellCount = document.getElementById('notification-count');
    if (bellCount) {
        var refreshBell = function () {
            fetch(bellCount.closest('a').href.replace('notifications.php', 'notifications_count.php'))
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    bellCount.textContent = data.count;
                    bellCount.hidden = data.count === 0;
                })
                .catch(function () {
                    // no network: keep the number that is already shown
                });
        };
        setInterval(refreshBell, 30000);
    }

    // Uses the local calendar date as the earliest booking date.NGL we used some helping tools for this part ;)
    var today = new Date();
    var year = today.getFullYear();
    var month = ('0' + (today.getMonth() + 1)).slice(-2);
    var day = ('0' + today.getDate()).slice(-2);
    var dateInputs = document.querySelectorAll('input[data-future-date]');
    for (var k = 0; k < dateInputs.length; k++) {
        dateInputs[k].min = year + '-' + month + '-' + day;
    }
});
