<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banners</title>
    <style>
        /* Estilos do layout principal */
        .carousel-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px 0;
        }

        .carousel-button {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            z-index: 10;
            margin: 0 15px;
        }

        .carousel-container {
            width: 830px; /* Ajustado para acomodar exatamente 3 banners */
            max-width: 90vw; /* Limitar largura em telas menores */
            overflow: hidden;
            position: relative;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-item {
            flex: 0 0 250px;
            margin: 0 10px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .carousel-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .carousel-container {
                width: 250px; /* Exatamente o tamanho do banner */
                max-width: 100vw;
                transform: none;
            }
            .carousel-item {
                margin: 0; /* Sem margem para não cortar imagem */
            }
            .carousel-button {
                display: none; /* Oculta as setas no mobile */
            }
        }
    </style>
</head>
<body>
    <div class="carousel-wrapper">
        <button class="carousel-button prev">&lt;</button>
        <div class="carousel-container">
            <div class="carousel-track">
                <?php
                // Lista de banners
                $banners = [
                    ['img' => '/divulgacao/img/netsapp.png', 'link' => 'https://netsapp.com.br'],
                    ['img' => '/divulgacao/img/netstrong.png', 'link' => 'https://netstrong.com.br'],
                    ['img' => '/divulgacao/img/saas.png', 'link' => 'https://wa.me/message/NZYMDF3IS6XZM1'],
                    ['img' => '/divulgacao/img/site_vendas.png', 'link' => 'https://wa.me/message/NZYMDF3IS6XZM1'],
                ];

                // Gerar HTML para os banners
                foreach ($banners as $banner) {
                    echo '<a href="' . htmlspecialchars($banner['link']) . '" target="_blank" class="carousel-item">';
                    echo '<img src="' . htmlspecialchars($banner['img']) . '" alt="Banner">';
                    echo '</a>';
                }
                ?>
            </div>
        </div>
        <button class="carousel-button next">&gt;</button>
    </div>

    <script>
        // Lógica do carrossel
        const track = document.querySelector('.carousel-track');
        const items = document.querySelectorAll('.carousel-item');
        const prevButton = document.querySelector('.carousel-button.prev');
        const nextButton = document.querySelector('.carousel-button.next');
        
        // Duplicar os itens para criar efeito de loop infinito
        const itemsArray = Array.from(items);
        
        // Clonar os primeiros itens para o final
        itemsArray.forEach(item => {
            const clone = item.cloneNode(true);
            track.appendChild(clone);
        });
        
        // Clonar os últimos itens para o início (em ordem reversa)
        itemsArray.slice().reverse().forEach(item => {
            const clone = item.cloneNode(true);
            track.insertBefore(clone, track.firstChild);
        });
        
        const allItems = document.querySelectorAll('.carousel-item');
        const itemWidth = allItems[0].getBoundingClientRect().width + 20; // Largura do item + margem
        let currentIndex = items.length; // Começar nos itens originais
        
        // Posicionar o carrossel nos itens originais
        track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
        
        let isTransitioning = false;
        let autoScroll = setInterval(showNext, 7000);

        function getItemWidth() {
            // No mobile, sem margem, no desktop, com margem
            if (window.innerWidth <= 768) {
                return allItems[0].getBoundingClientRect().width;
            }
            return allItems[0].getBoundingClientRect().width + 20;
        }

        function updateCarousel(transition = true) {
            const itemWidth = getItemWidth();
            if (transition) {
                track.style.transition = 'transform 0.5s ease-in-out';
            } else {
                track.style.transition = 'none';
            }
            track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
        }

        function showNext(force = false) {
            if (isTransitioning && !force) return;
            isTransitioning = true;
            currentIndex++;
            updateCarousel(true);

            track.addEventListener('transitionend', handleTransitionEndNext, { once: true });
        }

        function handleTransitionEndNext() {
            if (currentIndex >= items.length * 2) {
                currentIndex = items.length;
                updateCarousel(false);
            }
            isTransitioning = false;
        }

        function showPrev(force = false) {
            if (isTransitioning && !force) return;
            isTransitioning = true;
            currentIndex--;
            updateCarousel(true);

            track.addEventListener('transitionend', handleTransitionEndPrev, { once: true });
        }

        function handleTransitionEndPrev() {
            if (currentIndex < items.length) {
                currentIndex = items.length * 2 - 1;
                updateCarousel(false);
            }
            isTransitioning = false;
        }

        // Setas desktop
        prevButton.addEventListener('click', function() {
            showPrev(true);
        });
        nextButton.addEventListener('click', function() {
            showNext(true);
        });

        // Swipe/touch support
        let startX = 0;
        let isTouching = false;
        let touchMoved = false;

        track.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) {
                clearInterval(autoScroll);
                startX = e.touches[0].clientX;
                isTouching = true;
                touchMoved = false;
            }
        });

        track.addEventListener('touchmove', function(e) {
            if (isTouching) {
                touchMoved = true;
                e.preventDefault();
            }
        }, { passive: false });

        track.addEventListener('touchend', function(e) {
            if (!isTouching) return;
            let endX = e.changedTouches[0].clientX;
            let diff = endX - startX;
            if (Math.abs(diff) > 40) {
                if (diff < 0) {
                    showNext(true);
                } else {
                    showPrev(true);
                }
            }
            isTouching = false;
            autoScroll = setInterval(showNext, 7000);
        });

        // Auto scroll já definido acima

        function centerCarousel() {
            updateCarousel(false);
        }

        centerCarousel();
        window.addEventListener('resize', centerCarousel);
    </script>
</body>
</html>
