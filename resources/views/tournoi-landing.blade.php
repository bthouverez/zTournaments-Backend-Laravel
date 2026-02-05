<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TournamentPro - Gérez vos tournois comme un pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Work Sans', sans-serif;
            background: #0a0a0a;
            color: #ffffff;
            overflow-x: hidden;
        }

        .title-font {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 0.03em;
        }

        /* Animations */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

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

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(15, 64, 142, 0.5);
            }
            50% {
                box-shadow: 0 0 40px rgba(15, 64, 142, 0.8);
            }
        }

        .animate-slide-left {
            animation: slideInLeft 0.8s ease-out forwards;
        }

        .animate-slide-right {
            animation: slideInRight 0.8s ease-out forwards;
        }

        .animate-fade-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        .delay-400 { animation-delay: 0.4s; opacity: 0; }

        /* Gradient background */
        .gradient-bg {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%);
            position: relative;
        }

        .gradient-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(15, 64, 142, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(15, 64, 142, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Custom button hover */
        .btn-primary {
            background: linear-gradient(135deg, #0F408E 0%, #0A2B5E 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(15, 64, 142, 0.4);
        }

        /* Feature card hover */
        .feature-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(15, 64, 142, 0.5);
            background: rgba(15, 64, 142, 0.05);
        }

        /* Grid pattern overlay */
        .grid-pattern {
            background-image:
                linear-gradient(rgba(15, 64, 142, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 64, 142, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* Number counter style */
        .stat-number {
            background: linear-gradient(135deg, #0F408E 0%, #1E5BA8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-black/80 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 animate-slide-left">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#0F408E] to-[#0A2B5E] rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="title-font text-2xl text-white">zTournaments</span>
                </div>

                <div class="hidden md:flex items-center space-x-8 animate-slide-right">
{{--                    <a href="#features" class="text-gray-300 hover:text-[#0F408E] transition-colors">Fonctionnalités</a>--}}
{{--                    <a href="#pricing" class="text-gray-300 hover:text-[#0F408E] transition-colors">Tarifs</a>--}}
                    <a href="/register" class="text-gray-300 hover:text-[#0F408E] transition-colors">Inscription</a>
                    <a href="/login">
                        <button class="px-6 py-2 bg-[#0F408E] text-white rounded-lg hover:bg-[#0D3675] transition-colors font-medium">
                            Connexion
                        </button>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <button class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 gradient-bg grid-pattern overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div class="inline-block animate-fade-up">
                        <span class="px-4 py-2 bg-[#0F408E]/20 text-[#4A90E2] rounded-full text-sm font-semibold border border-[#0F408E]/30">
                            🏆 Plateforme n°1 à Civrieux
                        </span>
                    </div>

                    <h1 class="title-font text-6xl md:text-7xl leading-tight animate-fade-up delay-100">
                        Organisez vos <span class="text-[#0F408E]">tournois</span> en quelques clics
                    </h1>

                    <p class="text-xl text-gray-400 leading-relaxed animate-fade-up delay-200">
                        La solution complète pour gérer brackets, inscriptions et classements. Simple, rapide, professionnel.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 animate-fade-up delay-300">
                        <a href="/login"><button class="btn-primary px-8 py-4 rounded-lg text-white font-semibold text-lg shadow-lg">
                            Se connecter
                        </button></a>
                        <a href="/register"><button class="px-8 py-4 bg-white/10 hover:bg-white/20 rounded-lg text-white font-semibold text-lg border border-white/20 transition-all">
                            S'inscrire
                        </button></a>
                    </div>

                    <div class="flex items-center gap-8 pt-6 animate-fade-up delay-400">
                        <div class="flex -space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#0F408E] to-[#0A2B5E] border-2 border-black"></div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-cyan-500 border-2 border-black"></div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 border-2 border-black"></div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 border-2 border-black"></div>
                        </div>
                        <div class="text-gray-400">
                            <div class="font-semibold text-white">+12,000 organisateurs</div>
                            <div class="text-sm">nous font confiance</div>
                        </div>
                    </div>
                </div>

                <div class="relative animate-slide-right">
                    <div class="relative z-10 bg-gradient-to-br from-gray-900 to-black rounded-2xl border border-white/10 p-8 shadow-2xl">
                        <!-- Tournament Bracket Preview -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-4 border-b border-white/10">
                                <h3 class="text-lg font-bold">Finale - Coupe d'été</h3>
                                <span class="px-3 py-1 bg-green-600/20 text-green-500 text-xs rounded-full font-semibold">EN DIRECT</span>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg hover:bg-white/10 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-[#0F408E] to-[#0A2B5E] rounded-lg flex items-center justify-center font-bold">A</div>
                                        <div>
                                            <div class="font-semibold">Équipe Alpha</div>
                                            <div class="text-sm text-gray-400">Seed #1</div>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-bold text-[#4A90E2]">2</div>
                                </div>

                                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg hover:bg-white/10 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center font-bold">B</div>
                                        <div>
                                            <div class="font-semibold">Équipe Beta</div>
                                            <div class="text-sm text-gray-400">Seed #3</div>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-bold">1</div>
                                </div>
                            </div>

                            <div class="pt-4 grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-[#4A90E2]">156</div>
                                    <div class="text-xs text-gray-400">Participants</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-[#4A90E2]">24</div>
                                    <div class="text-xs text-gray-400">Matchs</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-[#4A90E2]">3j</div>
                                    <div class="text-xs text-gray-400">Durée</div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <!-- Decorative elements -->
        <div class="absolute top-1/4 left-10 w-72 h-72 bg-[#0F408E]/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-10 w-96 h-96 bg-[#1E5BA8]/10 rounded-full blur-3xl"></div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-black border-y border-white/10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center animate-fade-up">
                    <div class="stat-number text-5xl md:text-6xl font-bold title-font mb-2">12K+</div>
                    <div class="text-gray-400">Tournois organisés</div>
                </div>
                <div class="text-center animate-fade-up delay-100">
                    <div class="stat-number text-5xl md:text-6xl font-bold title-font mb-2">500K+</div>
                    <div class="text-gray-400">Joueurs inscrits</div>
                </div>
                <div class="text-center animate-fade-up delay-200">
                    <div class="stat-number text-5xl md:text-6xl font-bold title-font mb-2">98%</div>
                    <div class="text-gray-400">Satisfaction client</div>
                </div>
                <div class="text-center animate-fade-up delay-300">
                    <div class="stat-number text-5xl md:text-6xl font-bold title-font mb-2">24/7</div>
                    <div class="text-gray-400">Support disponible</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 gradient-bg">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16 animate-fade-up">
                <h2 class="title-font text-5xl md:text-6xl mb-6">
                    Tout ce dont vous avez besoin
                </h2>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    Une plateforme complète avec tous les outils pour gérer vos tournois de A à Z
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white/5 backdrop-blur-sm rounded-2xl p-8 animate-fade-up delay-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#0F408E] to-[#0A2B5E] rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Brackets automatiques</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Générez des tableaux éliminatoires, poules ou formats suisses en un clic. Mise à jour en temps réel.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card bg-white/5 backdrop-blur-sm rounded-2xl p-8 animate-fade-up delay-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#0F408E] to-[#0A2B5E] rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Inscriptions simplifiées</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Formulaires personnalisés, paiements en ligne, validation automatique. Tout est géré pour vous.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card bg-white/5 backdrop-blur-sm rounded-2xl p-8 animate-fade-up delay-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#0F408E] to-[#0A2B5E] rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Classements live</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Suivez les performances en direct. Statistiques détaillées, historiques et analyses complètes.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card bg-white/5 backdrop-blur-sm rounded-2xl p-8 animate-fade-up delay-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#0F408E] to-[#0A2B5E] rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Notifications push</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Alertez vos participants par email, SMS ou notifications. Communication instantanée et efficace.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card bg-white/5 backdrop-blur-sm rounded-2xl p-8 animate-fade-up delay-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#0F408E] to-[#0A2B5E] rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Planning intelligent</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Gestion des terrains, horaires optimisés, évitez les conflits. Tout est automatisé.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card bg-white/5 backdrop-blur-sm rounded-2xl p-8 animate-fade-up delay-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#0F408E] to-[#0A2B5E] rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Sécurité garantie</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Données cryptées, conformité RGPD, sauvegardes automatiques. Vos informations sont protégées.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-[#0F408E] to-[#1E5BA8]"></div>
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 w-full h-full grid-pattern"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <h2 class="title-font text-5xl md:text-6xl mb-6 animate-fade-up">
                Prêt à révolutionner vos tournois ?
            </h2>
            <p class="text-xl mb-12 text-white/90 animate-fade-up delay-100">
                Rejoignez les milliers d'organisateurs qui nous font confiance. Essai gratuit, sans carte de crédit.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-up delay-200">
                <button class="px-10 py-4 bg-white text-[#0F408E] rounded-lg font-bold text-lg hover:bg-gray-100 transition-all hover:scale-105 shadow-2xl">
                    Commencer gratuitement
                </button>
                <button class="px-10 py-4 bg-transparent border-2 border-white text-white rounded-lg font-bold text-lg hover:bg-white hover:text-[#0F408E] transition-all">
                    Planifier une démo
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black border-t border-white/10 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#0F408E] to-[#0A2B5E] rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="title-font text-2xl text-white">TournamentPro</span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        La plateforme de gestion de tournois la plus simple et la plus puissante.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Produit</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-[#4A90E2] transition-colors">Fonctionnalités</a></li>
                        <li><a href="#" class="hover:text-[#4A90E2] transition-colors">Tarifs</a></li>
                        <li><a href="#" class="hover:text-[#4A90E2] transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-[#4A90E2] transition-colors">API</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Entreprise</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-[#4A90E2] transition-colors">À propos</a></li>
                        <li><a href="#" class="hover:text-[#4A90E2] transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-[#4A90E2] transition-colors">Carrières</a></li>
                        <li><a href="#" class="hover:text-[#4A90E2] transition-colors">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Légal</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-[#4A90E2] transition-colors">Confidentialité</a></li>
                        <li><a href="#" class="hover:text-[#4A90E2] transition-colors">Conditions</a></li>
                        <li><a href="#" class="hover:text-[#4A90E2] transition-colors">Cookies</a></li>
                        <li><a href="#" class="hover:text-[#4A90E2] transition-colors">Licences</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-400 text-sm">
                    © 2026 TournamentPro. Tous droits réservés.
                </p>
                <div class="flex gap-6">
                    <a href="#" class="text-gray-400 hover:text-[#4A90E2] transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-[#4A90E2] transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-[#4A90E2] transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
