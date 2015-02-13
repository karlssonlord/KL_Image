# Magento Image Module
[![Build Status](https://magnum.travis-ci.com/karlssonlord/KL_Image.svg?token=yHMF4HM72xKhkhRWAR3d)](https://magnum.travis-ci.com/karlssonlord/KL_Image)

Provides a simple endpoint for scaling local images in a responsive manner.

## Implementation

Both markup, style and JavaScript is needed for best possible user experience. Below is a simple implementation example.

```html
<!-- 2:1 image example -->
<div class="img" style="padding-bottom:50%">
    <img data-src="path/to/orig/image.jpg" />
</div>
```

```css
.img {
    position: relative;
    padding-bottom: 100%;
}

.img img {
    position: absolute;
    width: 100%;
    height: 100%;
}
```

```javascript
$$('img').each(function(image) {
    var src = image.getAttribute('data-src');

    // Only handle adaptive images
    if(!src) return;

    var pixelRatio = window.devicePixelRatio || 1,
        imageWidth = Math.ceil(image.offsetWidth*pixelRatio/steps)*steps,
        imageUrl   = baseUrl+'image/index/index/'+src.match(/media\/(.*)/)[1]+'?w='+imageWidth;

    image.setAttribute('src', imageUrl);
});
```

## Resizing

Endpoint: /image/index/index/{image path}?w={requested width}

## Optimization

Optimization is done using [imgmin](https://github.com/rflynn/imgmin). If not installed (or available) the images will fall back to using dumb default 80% quality.