<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/ionicons@latest/dist/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="index_lp.css">

    <title>SMP dan SMA Pesantren MKGR Kertasemaya</title>
    <meta name="description" content="SMP dan SMA Pesantren MKGR Kertasemaya">
    <meta name="keywords" content="SMP dan SMA Pesantren MKGR Kertasemaya">
</head>

<body>
    <header class="l-header">
        <nav class="nav bd-grid">
            <div>
                <a href="#" class="nav__logo" id="inilogo">Presensi Guru</a>
            </div>

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item"><a href="#Beranda" class="nav__link active">Beranda</a></li>
                    <li class="nav__item"><a href="#Camera" class="nav__link">Camera</a></li>
                </ul>
            </div>

            <div class="nav__toggle" id="nav-toggle">
                <i class='bx bx-menu'></i>
            </div>
        </nav>
    </header>

    <main class="l-main">

        <section class="homes" id="Beranda">
            <div class="mainL">

                <div class="homeL">
                    <div class="slogan">
                        <h1 class="t">Presensi Guru<br><span class="u">berbasis Web</span></h1>
                        <p>Sistem presensi guru berbasis web memungkinkan para guru untuk melakukan absensi dengan cepat
                            dan mudah<br>di <b>SMP dan SMA Pesantren MKGR Kertasemaya</b>.</p>
                    </div>
                    <div class="action">
                        <div class="link">
                            <a href="login" class="button">Masuk</a>
                            <a href="daftar" class="button ln">Daftar</a>
                        </div>
                        <div class="flexIn baseline">
                            <div><a class="extL" href="#Camera">Selengkapnya</a></div>
                        </div>
                    </div>
                </div>

                <div class="homeR">
                    <img alt="Pics" src="https://presensi.feeldream.repl.co/pic4.png">
                    <img alt="Pics" src="https://presensi.feeldream.repl.co/pic2.png">
                    <img alt="Pics" src="https://presensi.feeldream.repl.co/pic3.png">
                </div>

            </div>
        </section>

        <!--===== ABOUT =====-->
        <!--<section class="about section " id="Tentang" style="display: none;">
            <h2 class="section-title">Tentang</h2>

            <div class="about__container bd-grid">
                <div class="about__img">
                    <img src="img/mkgr1.jpg" alt="">
                </div>
                
                <div>
                    <h2 class="about__subtitle">Contoh Judul</h2>
                    <p class="about__text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras tristique, sem vel blandit imperdiet, arcu erat aliquet dui, non suscipit libero orci venenatis massa. Nunc finibus nisi pellentesque lacus vehicula consequat.</p>           
                </div>                                   
            </div>
        </section>-->

        <section class="camera section" id="Camera">
            <h2 class="section-title">Camera</h2>
            <div class="about__container bd-grid">
                <div class="text-center" id="my_camera"></div>
                <div class="about__text">
                    <p style="text-align:justify;margin-bottom: 10px;">Kami menghadirkan terobosan inovasi terbaru
                        mengenai pengembangan teknologi sistem presensi untuk sekolah <b>SMP SMA MKGR KERTASEMAYA</b>
                        dengan mewajibkan pengguna berswafoto (selfie) sebagai tanda bukti yang membuat data absensi
                        semakin akurat dan meminimalisir kecurangan.</p>
                    <a class="button ln" onClick="ambil_gambar();"><svg class='line' xmlns='http://www.w3.org/2000/svg'
                            viewBox='0 0 24 24'>
                            <g transform='translate(2.500000, 3.042105)'>
                                <path
                                    d='M12.9381053,9.456 C12.9381053,7.71915789 11.5296842,6.31073684 9.79284211,6.31073684 C8.056,6.31073684 6.64757895,7.71915789 6.64757895,9.456 C6.64757895,11.1928421 8.056,12.6012632 9.79284211,12.6012632 C11.5296842,12.6012632 12.9381053,11.1928421 12.9381053,9.456 Z'>
                                </path>
                                <path
                                    d='M9.79252632,17.158 C17.8377895,17.158 18.7956842,14.7474737 18.7956842,9.52431579 C18.7956842,5.86326316 18.3114737,3.90431579 15.262,3.06221053 C14.982,2.97378947 14.6714737,2.80536842 14.4198947,2.52852632 C14.0135789,2.08326316 13.7167368,0.715894737 12.7356842,0.302210526 C11.7546316,-0.110421053 7.81463158,-0.0914736842 6.84936842,0.302210526 C5.88515789,0.696947368 5.57147368,2.08326316 5.16515789,2.52852632 C4.91357895,2.80536842 4.60410526,2.97378947 4.32305263,3.06221053 C1.27357895,3.90431579 0.789368421,5.86326316 0.789368421,9.52431579 C0.789368421,14.7474737 1.74726316,17.158 9.79252632,17.158 Z'>
                                </path>
                                <line x1='14.7045' y1='5.957895' x2='14.7135' y2='5.957895'></line>
                            </g>
                        </svg>Ambil Foto</a>
                </div>
            </div>

            <!-- webcamjs lewat LOKAL -->
            <script src="header/webcam.js"></script>

            <script language="JavaScript">
                Webcam.set({
                    width: 320,
                    height: 320,
                    image_format: 'jpeg',
                    jpeg_quality: 70
                });
                Webcam.attach('#my_camera');

                function ambil_gambar() {
                    Webcam.snap(function (data_uri) {
                        Swal.fire({
                            title: 'OK!',
                            text: 'Berhasil Melakukan Absensi',
                            imageUrl: '' + data_uri,
                            imageAlt: 'Swafoto',
                        })
                    });

                }
            </script>
        </section>

    </main>

    <footer class="footer">
        <p class="footer__title">Presensi Guru - SMP dan SMA Pesantren MKGR Kertasemaya</p>
        <!--<div class="footer__social">
            <a href="https://www.instagram.com/rayyarrr" class="footer__icon" target="_blank"><i class='icn instagram' ></i></a>
            <a href="https://www.tiktok.com/@rayy4r" class="footer__icon" target="_blank"><i class='icn tiktok' ></i></a>
            <a href="https://github.com/feeldreams" class="footer__icon" target="_blank"><i class='icn github' ></i></a>
        </div>-->
        <p class="footer__copy">&#169; 2023 - By Kelompok 3 - Rayya, Aini, Hilal</p>
    </footer>

    <script src="https://unpkg.com/scrollreveal"></script>

    <script>
        const showMenu = (toggleId, navId) => {
            const toggle = document.getElementById(toggleId),
                nav = document.getElementById(navId)

            if (toggle && nav) {
                toggle.addEventListener('click', () => {
                    nav.classList.toggle('show')
                })
            }
        }
        showMenu('nav-toggle', 'nav-menu')

        const navLink = document.querySelectorAll('.nav__link')

        function linkAction() {
            const navMenu = document.getElementById('nav-menu')
            navMenu.classList.remove('show')
        }
        navLink.forEach(n => n.addEventListener('click', linkAction))

        const sections = document.querySelectorAll('section[id]')
        idweb = inilogo.innerHTML

        function scrollActive() {
            const scrollY = window.pageYOffset

            sections.forEach(current => {
                const sectionHeight = current.offsetHeight
                const sectionTop = current.offsetTop - 50;
                sectionId = current.getAttribute('id')

                if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                    document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.add('active');
                    inilogo.innerHTML = sectionId;
                } else {
                    document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.remove('active')
                }

                if (sectionId == "Beranda") { inilogo.innerHTML = idweb; }
            })
        }
        window.addEventListener('scroll', scrollActive)

        // Animasi Teks

        const sr = ScrollReveal({
            origin: 'top',
            distance: '40px',
            duration: 1500,
            delay: 150,
            reset: true
        });

        sr.reveal('.homeL, .about__img, .skills__subtitle, .skills__text', {});
        sr.reveal('.homeR, .about__subtitle, .about__text, .skills__img, video', { delay: 200 });
        sr.reveal('.home__social-icon', { interval: 200 });
        sr.reveal('.skills__data, .work__img, .contact__input, .dlBox', { interval: 200 });

    </script>
</body>
</html>