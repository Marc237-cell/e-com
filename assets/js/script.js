// Video Slideshow Functionality
class VideoSlideshow {
    constructor() {
        this.slides = document.querySelectorAll('.video-slide');
        this.indicators = document.querySelectorAll('.video-indicator');
        this.prevBtn = document.querySelector('.video-prev');
        this.nextBtn = document.querySelector('.video-next');
        this.playPauseBtn = document.querySelector('.video-play-pause');
        this.currentSlide = 0;
        this.isPlaying = true;
        this.slideInterval = null;
        
        this.init();
    }
    
    init() {
        // Start the slideshow
        this.startSlideshow();
        
        // Event listeners
        this.prevBtn.addEventListener('click', () => this.prevSlide());
        this.nextBtn.addEventListener('click', () => this.nextSlide());
        this.playPauseBtn.addEventListener('click', () => this.togglePlayPause());
        
        // Indicator clicks
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => this.goToSlide(index));
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prevSlide();
            if (e.key === 'ArrowRight') this.nextSlide();
            if (e.key === ' ') {
                e.preventDefault();
                this.togglePlayPause();
            }
        });
        
        // Handle video ended event to auto-advance
        this.slides.forEach(slide => {
            const video = slide.querySelector('video');
            video.addEventListener('ended', () => {
                if (this.isPlaying) {
                    this.nextSlide();
                }
            });
        });
    }
    
    startSlideshow() {
        this.slideInterval = setInterval(() => {
            if (this.isPlaying) {
                this.nextSlide();
            }
        }, 9000); // Change slide every 9 seconds
    }
    
    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        this.updateSlides();
    }
    
    prevSlide() {
        this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.updateSlides();
    }
    
    goToSlide(index) {
        this.currentSlide = index;
        this.updateSlides();
    }
    
    updateSlides() {
        // Update slides
        this.slides.forEach((slide, index) => {
            slide.classList.toggle('active', index === this.currentSlide);
            
            // Play/pause videos
            const video = slide.querySelector('video');
            if (index === this.currentSlide && this.isPlaying) {
                video.play().catch(e => console.log('Video play failed:', e));
            } else {
                video.pause();
                video.currentTime = 0;
            }
        });
        
        // Update indicators
        this.indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === this.currentSlide);
        });
        
        // Reset interval
        if (this.isPlaying) {
            clearInterval(this.slideInterval);
            this.startSlideshow();
        }
    }
    
    togglePlayPause() {
        this.isPlaying = !this.isPlaying;
        
        const playIcon = this.playPauseBtn.querySelector('.play-icon');
        const pauseIcon = this.playPauseBtn.querySelector('.pause-icon');
        
        if (this.isPlaying) {
            playIcon.style.display = 'none';
            pauseIcon.style.display = 'block';
            this.slides[this.currentSlide].querySelector('video').play();
            this.startSlideshow();
        } else {
            playIcon.style.display = 'block';
            pauseIcon.style.display = 'none';
            this.slides[this.currentSlide].querySelector('video').pause();
            clearInterval(this.slideInterval);
        }
    }
}

// Initialize the video slideshow when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new VideoSlideshow();
});