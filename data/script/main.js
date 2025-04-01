/* // Attendez que le contenu de la fenêtre soit chargé avant d'exécuter le code
window.addEventListener('DOMContentLoaded', (event) => {
        // Sélectionnez la première balise 'div' avec la classe page dans le document
        const page = document.querySelector('.page');

        const section = document.querySelector('section');

        const article = document.querySelector('article');

        // Vérifiez si la hauteur de l'article est inférieure ou égale à la hauteur de la section
        if (article.clientHeight <= section.clientHeight) {
            // Si c'est le cas, définissez la hauteur de la section sur 100%
            page.style.height = "100vh";
            section.style.height = "100%";
        } else {
            // Sinon, définissez la hauteur de la section sur 'auto', ce qui permettra à la hauteur de s'ajuster automatiquement
            console.log(page.clientHeight);
            page.style.height = "100%";
            section.style.height = "auto";
        }
}); */