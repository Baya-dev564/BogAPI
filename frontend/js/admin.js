// BlogAPI - JavaScript pour panel d'administration
// Développé par Baya AMELLAL PAYAN

class AdminPanel {
    constructor() {
        // Je définis l'URL de base de mon API
        this.apiBaseUrl = 'http://blogapi.test/public/index.php?url=api';
        
        // Je initialise le panel admin
        this.init();
    }

    /**
     * Je initialise le panel d'administration
     */
    init() {
        // Je configure le formulaire d'ajout d'article
        this.setupAddArticleForm();
        
        // Je charge les statistiques
        this.loadStats();
        
        console.log('Panel Admin initialisé - Développé par Baya AMELLAL PAYAN');
    }

    /**
     * Je configure le formulaire d'ajout d'article
     */
    setupAddArticleForm() {
        const form = document.getElementById('add-article-form');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.addArticle();
            });
        }
    }

    /**
     * Je ajoute un nouvel article via l'API
     */
    async addArticle() {
        try {
            const title = document.getElementById('article-title').value;
            const content = document.getElementById('article-content').value;
            const categoryId = document.getElementById('article-category').value;
            const authorId = document.getElementById('article-author').value;

            // Je génère un slug à partir du titre
            const slug = title.toLowerCase()
                              .replace(/[éèê]/g, 'e')
                              .replace(/[àâ]/g, 'a')
                              .replace(/[ç]/g, 'c')
                              .replace(/[^a-z0-9]/g, '-')
                              .replace(/--+/g, '-')
                              .replace(/^-|-$/g, '');

            const articleData = {
                title: title,
                slug: slug,
                content: content,
                category_id: categoryId,
                author_id: authorId,
                status: 'published'
            };

            console.log('Données envoyées:', articleData);

            const response = await fetch(`${this.apiBaseUrl}/articles`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(articleData)
            });

            const result = await response.json();
            console.log('Réponse API:', result);

            if (result.success) {
                this.showResult('add-result', 'Article créé avec succès !', 'success');
                document.getElementById('add-article-form').reset();
                // Je recharge les stats
                this.loadStats();
            } else {
                this.showResult('add-result', `Erreur: ${result.message}`, 'error');
            }

        } catch (error) {
            console.error('Erreur:', error);
            this.showResult('add-result', `Erreur: ${error.message}`, 'error');
        }
    }

    /**
     * Je teste un endpoint de l'API
     */
    async testAPI(endpoint) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/${endpoint}`);
            const data = await response.json();
            
            document.getElementById('api-results').textContent = JSON.stringify(data, null, 2);
        } catch (error) {
            document.getElementById('api-results').textContent = `Erreur: ${error.message}`;
        }
    }

    /**
     * Je charge les statistiques
     */
    async loadStats() {
        try {
            // Je récupère toutes les données
            const [articlesRes, usersRes, categoriesRes] = await Promise.all([
                fetch(`${this.apiBaseUrl}/articles`),
                fetch(`${this.apiBaseUrl}/users`),
                fetch(`${this.apiBaseUrl}/categories`)
            ]);

            const articles = await articlesRes.json();
            const users = await usersRes.json();
            const categories = await categoriesRes.json();

            // Je affiche les statistiques
            const statsContainer = document.getElementById('stats-container');
            statsContainer.innerHTML = `
                <div class="stat-card">
                    <h3><i class="fas fa-newspaper"></i> Articles</h3>
                    <div class="stat-number">${articles.data?.length || 0}</div>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-users"></i> Utilisateurs</h3>
                    <div class="stat-number">${users.data?.length || 0}</div>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-tags"></i> Catégories</h3>
                    <div class="stat-number">${categories.data?.length || 0}</div>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-eye"></i> Total Vues</h3>
                    <div class="stat-number">${articles.data?.reduce((total, article) => total + (article.view_count || 0), 0) || 0}</div>
                </div>
            `;

        } catch (error) {
            console.error('Erreur stats:', error);
        }
    }

    /**
     * Je affiche un résultat
     */
    showResult(elementId, message, type) {
        const element = document.getElementById(elementId);
        element.textContent = message;
        element.className = `result ${type}`;
        element.style.display = 'block';
    }
}

// Je rends la fonction testAPI globale
let adminPanel;

function testAPI(endpoint) {
    if (adminPanel) {
        adminPanel.testAPI(endpoint);
    }
}

// Je initialise le panel admin
document.addEventListener('DOMContentLoaded', () => {
    adminPanel = new AdminPanel();
});
