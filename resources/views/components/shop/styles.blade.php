<style>
        .whatsapp-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 999;
            cursor: pointer;
        }
        .custom-radio-group {
            display: flex;
            gap: 0.625rem;
            overflow-x: auto;
            padding-bottom: 0.625rem;
        }
        .custom-radio-group label {
            cursor: pointer;
        }
        .custom-radio-group input[type="radio"] {
            display: none;
        }
        .custom-radio-group input[type="radio"]:checked + label {
            color: orange;
        }
        /* Fake Sales Notification */
        #product-alert {
            width: 350px;
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            display: none;
            align-items: flex-start;
            gap: 0.5rem;
            cursor: pointer;
            z-index: 999;
            background-color: white;
        }

        .product-info {
            display: flex;
        }

        .product-info img {
            width: 100px;
            height: 100px;
            margin-right: 10px;
        }

        .message {
            font-size: 16px;
        }

        .time {
            font-size: 14px;
            margin-top: 16px;
            color: #a0a0a0;
        }
        #close-btn {
            background-color: transparent;
            color: red;
            border: none;
            outline: none;
        }
        .small-laptop-img {
            width: 80%;
            max-width: 400px;
            max-height: fit-content;
            margin: 0 auto;
        }
        /* Fake Sales Notification */

        .image-container {
            width: 100%; /* Menggunakan lebar penuh dari kolom */
            height: 100px;
            overflow: hidden; /* Menyembunyikan bagian gambar yang keluar */
            position: relative; /* Untuk positioning gambar */
        }

        .image-container img {
            width: 100%; /* Gambar mengisi lebar kontainer 100% */
            height: 100%; /* Gambar mengisi tinggi kontainer 100% */
            object-fit: contain; /* Memastikan gambar mengisi kontainer tanpa merusak proporsi */
            position: absolute; /* Posisi absolut untuk gambar */
            top: 0; /* Mengatur gambar dari atas kontainer */
            left: 0; /* Mengatur gambar dari kiri kontainer */
        }

        @media (max-width: 767.98px) {
            .custom-radio-group {
                flex-wrap: nowrap;
            }
            .custom-radio-group label {
                border: 0.125rem solid orange;
            }
            .custom-radio-group input[type="radio"] {
                display: none;
            }
            .custom-radio-group label {
                padding: 0.625rem 1.25rem;
                border-radius: 1.875rem;
                color: orange;
                cursor: pointer;
                white-space: nowrap;
            }
            .custom-radio-group input[type="radio"]:checked + label {
                background-color: orange;
                color: white;
            }
        }
        .card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        @media (min-width: 768px) {
            .custom-radio-group {
                flex-direction: column;
            }
            .custom-radio-group label {
                border: none;
            }
        }
    </style>
