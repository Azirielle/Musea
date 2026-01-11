<?php

namespace App\Enums;

enum ArtworkSubcategory: string
{
    // Painting
    case Oil = 'Oil';
    case Acrylic = 'Acrylic';
    case Watercolor = 'Watercolor';
    case Abstract = 'Abstract';
    case Portrait = 'Portrait';

    // Digital
    case ThreeDRender = '3D Render';
    case Vector = 'Vector';
    case AIArt = 'AI Art';
    case PixelArt = 'Pixel Art';

    // Sculpture
    case Metal = 'Metal';
    case Wood = 'Wood';
    case Resin = 'Resin';
    case Ceramic = 'Ceramic';

    // Drawing
    case Graphite = 'Graphite';
    case Charcoal = 'Charcoal';
}
