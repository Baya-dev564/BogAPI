// BlogAPI - JavaScript pour interface web
// Développé par Baya AMELLAL PAYAN

class BlogAPI {
    constructor() {
        // Je définis l'URL de base de mon API
        this.apiBaseUrl = 'http://blogapi.test/public/api';
        
        // Je initialise l'application
        this.init();
    }

    /**
     * Je initialise l'application au chargement de la page
     */
    init() {
        // Je charge les articles au démarrage
        this.loadArticles();
        
        // Je configure la navigation
        this.setupNavigation();
        
        console.log('BlogAPI Frontend initialisé - Développé par Baya AMELLAL PAYAN');
    }

    /**
 * Je configure les événements de navigation
 */
setupNavigation() {
    const navLinks = document.querySelectorAll('.nav a');
    
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            // JE NE BLOQUE PAS le lien admin !
            if (link.classList.contains('admin-link')) {
                return; // Je laisse le lien normal fonctionner
            }
            
            e.preventDefault();
            
            // Je retire la classe active
            navLinks.forEach(l => l.classList.remove('active'));
            // Je l'ajoute au lien cliqué
            link.classList.add('active');
            
            // Je affiche la section correspondante
            const section = link.getAttribute('href').replace('#', '');
            this.showSection(section);
        });
    });
}

    /**
     * Je affiche une section spécifique
     */
    showSection(sectionName) {
        // Je cache toutes les sections
        document.getElementById('articles-section').style.display = 'none';
        document.getElementById('categories-section').style.display = 'none';
        
        switch(sectionName) {
            case 'home':
            case 'articles':
                document.getElementById('articles-section').style.display = 'block';
                if (!this.articlesLoaded) {
                    this.loadArticles();
                }
                break;
                
            case 'categories':
                document.getElementById('categories-section').style.display = 'block';
                if (!this.categoriesLoaded) {
                    this.loadCategories();
                }
                break;
        }
    }

    /**
     * Je charge la liste des articles depuis l'API
     */
    async loadArticles() {
        try {
            // Je affiche le loading
            this.showLoading('articles-loading');
            this.hideError('articles-error');
            
            // Je appelle mon API
            const response = await fetch(`${this.apiBaseUrl}/articles`);
            const data = await response.json();
            
            if (data.success) {
                this.displayArticles(data.data);
                this.articlesLoaded = true;
            } else {
                throw new Error(data.message || 'Erreur lors du chargement');
            }
            
        } catch (error) {
            console.error('Erreur:', error);
            this.showError('articles-error', `Erreur: ${error.message}`);
        } finally {
            this.hideLoading('articles-loading');
        }
    }

    /**
     * Je affiche les articles dans le HTML
     */
    displayArticles(articles) {
        const container = document.getElementById('articles-container');
        
        if (articles.length === 0) {
            container.innerHTML = '<p class="no-data">Aucun article trouvé.</p>';
            return;
        }
        
        container.innerHTML = articles.map(article => `
            <article class="article-card" onclick="blogApi.viewArticle('${article.slug}')">
                <h3>${article.title}</h3>
                <div class="meta">
                    <span><i class="fas fa-user"></i> ${article.first_name} ${article.last_name}</span>
                    <span><i class="fas fa-tag"></i> ${article.category_name}</span>
                    <span><i class="fas fa-eye"></i> ${article.view_count} vues</span>
                </div>
                <div class="excerpt">
                    ${article.excerpt || article.content.substring(0, 150) + '...'}
                </div>
                <a href="#" class="read-more">
                    Lire la suite <i class="fas fa-arrow-right"></i>
                </a>
            </article>
        `).join('');
    }

    /**
     * Je charge la liste des catégories
     */
    async loadCategories() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/categories/count`);
            const data = await response.json();
            
            if (data.success) {
                this.displayCategories(data.data);
                this.categoriesLoaded = true;
            }
            
        } catch (error) {
            console.error('Erreur catégories:', error);
        }
    }

    /**
     * Je affiche les catégories
     */
    displayCategories(categories) {
        const container = document.getElementById('categories-container');
        
        container.innerHTML = categories.map(category => `
            <div class="category-card">
                <h3>${category.name}</h3>
                <div class="count">${category.article_count} articles</div>
            </div>
        `).join('');
    }

    /**
     * Je affiche un article spécifique (sera implémenté après)
     */
    viewArticle(slug) {
        alert(`Voir l'article: ${slug}\n(Page détail à implémenter)`);
    }

    /**
     * Je affiche le loading
     */
    showLoading(elementId) {
        document.getElementById(elementId).style.display = 'block';
    }

    /**
     * Je cache le loading
     */
    hideLoading(elementId) {
        document.getElementById(elementId).style.display = 'none';
    }

    /**
     * J'affiche une erreur
     */
    showError(elementId, message) {
        const element = document.getElementById(elementId);
        element.textContent = message;
        element.style.display = 'block';
    }

    /**
     * Je cache une erreur
     */
    hideError(elementId) {
        document.getElementById(elementId).style.display = 'none';
    }
}

// Je initialise l'application quand la page est chargée
let blogApi;
document.addEventListener('DOMContentLoaded', () => {
    blogApi = new BlogAPI();
});
