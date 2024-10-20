<script>
    // Fake Sales Notification
    const productAlert = document.getElementById("product-alert");
    const productInfo = document.getElementById("product-info");
    const closeAlertBtn = document.getElementById("close-btn");
    const productText = document.getElementById("product-text");
    const productImage = document.getElementById("product-image");
    const leads = @json($leads);
    const products = @json($productsJson);
    let alertTimeout;
    let currentLeadIndex = leads.length - 1; // Mulai dari index terbesar
    let leadCount = 0; // Menghitung jumlah lead yang telah ditampilkan

    const getNextLead = () => {
        if (leadCount >= 3) {
            clearTimeout(alertTimeout); // Hentikan setelah 3 lead ditampilkan
            return;
        }

        const lead = leads[currentLeadIndex];
        currentLeadIndex--; // Kurangi index untuk mengambil lead sebelumnya
        leadCount++; // Tambahkan hitungan lead yang telah ditampilkan
        return lead;
    };
    const showAlert = () => {
    const nextLead = getNextLead();
    const product = products.find(p => p.id === nextLead.product_id);

    if (!product) {
        console.log(product.id);
        console.error('Product not found');
        return;
    }

    productImage.src = `{{ url('storage/products') }}/${product.main_image.replace('public/products/', '')}`;

    if (!productImage.src) {
        console.error('Image source is invalid or not found');
    }

    // Batasi panjang product.name hingga 24 karakter
    let productName = product.name.length > 24 ? product.name.slice(0, 24) + '...' : product.name;

    // Menampilkan pesan tanpa waktu order
    productText.innerHTML = `<p class="message"><b>${nextLead.name}</b> dari <b>${nextLead.regency}</b> baru saja membeli <b>${productName}</b></p>`;

    if (productInfo) {
        productInfo.onclick = () => window.location.href = `{{ url('product/detail') }}/${product.slug}`;
    } else {
        console.error('Product info element not found');
    }

    productAlert.style.display = "flex";
    alertTimeout = setTimeout(() => {
        productAlert.style.display = "none";
        showAlert();
    }, 5000);
};
    closeAlertBtn.addEventListener("click", () => {
        clearTimeout(alertTimeout);
        productAlert.style.display = "none";
        setTimeout(showAlert, getRandomDisplayTime() * 1000);
    });

    document.addEventListener("DOMContentLoaded", () => {
        showAlert();
    });
    // Fake Sales Notification

    function shareProduct(url, productName) {
        const encodedUrl = encodeURIComponent(url);
        const encodedProductName = encodeURIComponent(productName);

        const fbShare = `<a href="https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}" target="_blank" class="btn btn-primary mb-2"><i class="fab fa-facebook-f"></i> Facebook</a>`;
        const waShare = `<a href="https://wa.me/?text=${encodedProductName}%20${encodedUrl}" target="_blank" class="btn btn-success mb-2"><i class="fab fa-whatsapp"></i> WhatsApp</a>`;
        const igShare = `<a href="https://www.instagram.com/?url=${encodedUrl}" target="_blank" class="btn btn-danger mb-2"><i class="fab fa-instagram"></i> Instagram</a>`;
        const telegramShare = `<a href="https://telegram.me/share/url?url=${encodedUrl}&text=${encodedProductName}" target="_blank" class="btn btn-info mb-2"><i class="fab fa-telegram-plane"></i> Telegram</a>`;
        const twitterShare = `<a href="https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedProductName}" target="_blank" class="btn btn-primary mb-2" style="background-color: #1DA1F2;"><i class="fab fa-twitter"></i> Twitter</a>`;

        document.getElementById('share-buttons-container').innerHTML = `${fbShare} ${waShare} ${igShare} ${telegramShare} ${twitterShare}`;

        const shareProductModal = new bootstrap.Modal(document.getElementById('shareProductModal'));
        shareProductModal.show();
    }
    // Share Product

    // Filter Form
    document.querySelectorAll('input[name="kategori"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
    // Filter Form
    const testimonials = @json($testimonials);
    const testimonialContainer = document.getElementById('testimonialContainer');

    function createSlides(testimonials, chunkSize) {
        testimonialContainer.innerHTML = ''; // Kosongkan kontainer sebelum memasukkan elemen baru

        // Base URL untuk mengambil gambar dari storage
        const baseUrl = "{{ url('storage/shops') }}";

        // Chunking the testimonials array
        for (let i = 0; i < testimonials.length; i += chunkSize) {
            const testimonialChunk = testimonials.slice(i, i + chunkSize);
            const isActive = i === 0 ? 'active' : '';

            // Membuat carousel item dan grid kolom untuk setiap chunk
            let slide = `
                <div class="carousel-item ${isActive}">
                    <div class="row">`;

            testimonialChunk.forEach(testimonial => {
                // Menghapus 'public/shops/' dari testimonial.testimoni_url dan membuat URL yang benar
                const imageUrl = `${baseUrl}/${testimonial.testimoni_url.replace('public/shops/', '')}`;

                const imgClass = chunkSize === 3 ? 'small-laptop-img' : '';
                slide += `
                    <div class="col">
                        <img src="${imageUrl}" class="d-block img-fluid ${imgClass}" alt="Testimonial">
                    </div>
                `;
            });

            slide += `</div></div>`;
            testimonialContainer.insertAdjacentHTML('beforeend', slide);
        }
    }

    function updateCarousel() {
        const screenWidth = window.innerWidth;

        let chunkSize;
        if (screenWidth < 768) {
            chunkSize = 1; // Smartphone: 1 testimonial per slide
        } else if (screenWidth >= 768 && screenWidth < 992) {
            chunkSize = 2; // Tablet: 2 testimonials per slide
        } else {
            chunkSize = 3; // Laptop: 3 testimonials per slide
        }

        createSlides(testimonials, chunkSize);
    }
    $(document).ready(function(){
    setInterval(function(){
        $('#carouselEkspedisi').carousel('next');
    }, 2000);
});

    window.addEventListener('resize', updateCarousel);
    window.addEventListener('DOMContentLoaded', updateCarousel);
</script>
