<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>zTournaments - Plateforme de Gestion de Tournois Sportifs</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                line-height: 1.6;
                color: #333;
                overflow-x: hidden;
            }

            /* Header */
            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                padding: 1rem 2rem;
                z-index: 1000;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }

            .nav-container {
                max-width: 1280px;
                margin: 0 auto;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .logo {
                font-size: 1.5rem;
                font-weight: 800;
                color: #2563eb;
                text-decoration: none;
            }

            .nav-links {
                display: flex;
                gap: 2rem;
                align-items: center;
            }

            .nav-links a {
                text-decoration: none;
                color: #4b5563;
                font-weight: 500;
                transition: color 0.3s;
            }

            .nav-links a:hover {
                color: #2563eb;
            }

            .btn {
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s;
                display: inline-block;
            }

            .btn-primary {
                background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            }

            .btn-secondary {
                background: white;
                color: #2563eb;
                border: 2px solid #2563eb;
            }

            .btn-secondary:hover {
                background: #2563eb;
                color: white;
            }

            /* Hero Section */
            .hero {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                position: relative;
                overflow: hidden;
                padding: 2rem;
            }

            .hero::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
                opacity: 0.3;
            }

            .hero-content {
                max-width: 1280px;
                width: 100%;
                text-align: center;
                color: white;
                position: relative;
                z-index: 1;
            }

            .hero h1 {
                font-size: 3.5rem;
                font-weight: 800;
                margin-bottom: 1.5rem;
                line-height: 1.2;
                animation: fadeInUp 1s ease-out;
            }

            .hero p {
                font-size: 1.25rem;
                margin-bottom: 2.5rem;
                opacity: 0.95;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
                animation: fadeInUp 1s ease-out 0.2s backwards;
            }

            .hero-buttons {
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
                animation: fadeInUp 1s ease-out 0.4s backwards;
            }

            /* Features Section */
            .features {
                padding: 6rem 2rem;
                background: #f9fafb;
            }

            .section-title {
                text-align: center;
                font-size: 2.5rem;
                font-weight: 800;
                color: #1f2937;
                margin-bottom: 1rem;
            }

            .section-subtitle {
                text-align: center;
                font-size: 1.125rem;
                color: #6b7280;
                margin-bottom: 4rem;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }

            .features-grid {
                max-width: 1280px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
            }

            .feature-card {
                background: white;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                transition: all 0.3s;
            }

            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1.5rem;
            }

            .feature-icon svg {
                width: 30px;
                height: 30px;
                stroke: white;
            }

            .feature-card h3 {
                font-size: 1.5rem;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 1rem;
            }

            .feature-card p {
                color: #6b7280;
                line-height: 1.7;
            }

            /* CTA Section */
            .cta {
                padding: 6rem 2rem;
                background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                color: white;
                text-align: center;
            }

            .cta h2 {
                font-size: 2.5rem;
                font-weight: 800;
                margin-bottom: 1.5rem;
            }

            .cta p {
                font-size: 1.25rem;
                margin-bottom: 2.5rem;
                opacity: 0.95;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }

            /* Footer */
            .footer {
                background: #1f2937;
                color: white;
                padding: 3rem 2rem;
                text-align: center;
            }

            .footer p {
                opacity: 0.8;
            }

            /* Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsive */
            @media (max-width: 768px) {
                .hero h1 {
                    font-size: 2.5rem;
                }

                .hero p {
                    font-size: 1rem;
                }

                .section-title {
                    font-size: 2rem;
                }

                .nav-links {
                    gap: 1rem;
                }

                .hero-buttons {
                    flex-direction: column;
                    align-items: center;
                }

                .btn {
                    width: 100%;
                    max-width: 300px;
                }
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <header class="header">
            <nav class="nav-container">
                <a href="/" class="logo">⚡ zTournaments</a>
                <div class="nav-links">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}">Connexion</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">S'inscrire</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Organisez vos Tournois Sportifs en Toute Simplicité</h1>
                <p>La plateforme complète pour créer, gérer et suivre vos compétitions sportives. Brackets, scores en temps réel, et bien plus encore.</p>
                <div class="hero-buttons">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Créer un Tournoi</a>
                    @endif
                    <a href="#features" class="btn btn-secondary">Découvrir</a>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features" id="features">
            <h2 class="section-title">Tout ce dont vous avez besoin</h2>
            <p class="section-subtitle">Des outils puissants pour organiser des tournois professionnels</p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3>Brackets Automatiques</h3>
                    <p>Générez automatiquement des brackets à élimination simple ou double. Gérez les matchs et suivez la progression en temps réel.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3>Scores en Direct</h3>
                    <p>Mettez à jour les scores en temps réel. Les participants et spectateurs voient instantanément les résultats.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3>Gestion d'Équipes</h3>
                    <p>Inscrivez facilement des équipes et des joueurs. Gérez les informations et communications depuis un seul endroit.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3>Inscription Simplifiée</h3>
                    <p>Les participants s'inscrivent en quelques clics. Formulaires personnalisables et validation automatique.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                    </div>
                    <h3>Statistiques Détaillées</h3>
                    <p>Analysez les performances avec des statistiques complètes. Exportez les données pour vos rapports.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3>100% Responsive</h3>
                    <p>Accédez à vos tournois depuis n'importe quel appareil. Interface optimisée pour mobile, tablette et desktop.</p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta">
            <h2>Prêt à Organiser Votre Prochain Tournoi ?</h2>
            <p>Rejoignez des milliers d'organisateurs qui font confiance à zTournaments pour leurs compétitions sportives.</p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-secondary">Commencer Gratuitement</a>
            @endif
        </section>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; {{ date('Y') }} zTournaments. Tous droits réservés.</p>
            <p style="margin-top: 0.5rem; font-size: 0.875rem;">
                Plateforme de gestion de tournois sportifs • Laravel {{ Illuminate\Foundation\Application::VERSION }}
            </p>
        </footer>
    </body>
</html>
