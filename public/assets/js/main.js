document.addEventListener('DOMContentLoaded', function () {
    function handleMouseOver(event) {
        const element = event.currentTarget;
        const id = element.getAttribute('data-id');
        const descElement = element.querySelector('.item-info__desc');

        fetch(`/get-description?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.description) {
                    descElement.textContent = data.description;
                } else {
                    descElement.textContent = 'Description not available.';
                }
            })
            .catch(() => {
                descElement.textContent = '';
            });
    }

    document.querySelectorAll('.item-info').forEach(element => {
        element.addEventListener('mouseover', handleMouseOver);
    });
});