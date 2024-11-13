document.addEventListener('DOMContentLoaded', () => {
    const addImage = document.querySelector('#add-image');
    const widgetCounter = document.querySelector('#widgets-counter');

    if (!addImage) {
        console.warn("Element #add-image not found on the page.");
        return;
    }

    if (!widgetCounter) {
        console.warn("Element #widgets-counter not found on the page.");
        return;
    }

    // Event listener for adding images
    addImage.addEventListener('click', () => {
        const index = +widgetCounter.value; // Convert value to a number
        const annonceImages = document.querySelector('#car_images');
        console.log(annonceImages);
        // Retrieve and replace the prototype's placeholders
        const prototype = annonceImages.dataset.prototype.replace(/__name__/g, index);
        annonceImages.insertAdjacentHTML('beforeend', prototype);

        widgetCounter.value = index + 1;
        handleDeleteButtons();
    });

    const updateCounter = () => {
        if (widgetCounter) {
            const count = document.querySelectorAll('#car_images div.form-group').length;
            widgetCounter.value = count;
        }
    };

    const handleDeleteButtons = () => {
        const deleteButtons = document.querySelectorAll("button[data-action='delete']");
        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const target = button.dataset.target;
                const elementTarget = document.querySelector(target);
                if (elementTarget) {
                    elementTarget.remove();
                }
            });
        });
    };

    updateCounter();
    handleDeleteButtons();
});