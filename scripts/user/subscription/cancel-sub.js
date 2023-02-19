window.onload = function () {
    document.getElementById('cancel').addEventListener('submit', function (e) {
        e.preventDefault();

        fetch('controllers/user/cancel_subscription.php', {
            method: 'post',
            body: JSON.stringify(Object.fromEntries(new FormData(document.forms.item(0)).entries())),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    document.getElementById('form-success').classList.remove('hidden');
                    document.getElementById('form-error').classList.add('hidden');
                } else {
                    document.getElementById('form-error').classList.remove('hidden');
                    document.getElementById('form-success').classList.add('hidden');
                }
            });
    });
};