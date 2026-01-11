<?php

namespace App\Enums;

enum ArtworkCategory: string
{
    case Painting = 'Painting';
    case Digital = 'Digital';
    case Sculpture = 'Sculpture';
    case Photography = 'Photography';
    case MixedMedia = 'Mixed Media';
    case Drawing = 'Drawing';

    // Legacy support (optional, can be removed if data is migrated)
    case Canvas = 'Canvas';
    case Vase = 'Vase';
    case Basket = 'Basket';
    case Other = 'Other';

    public function subcategories(): array
    {
        return match ($this) {
            self::Painting => [
                ArtworkSubcategory::Oil,
                ArtworkSubcategory::Acrylic,
                ArtworkSubcategory::Watercolor,
                ArtworkSubcategory::Abstract ,
                ArtworkSubcategory::Portrait,
            ],
            self::Digital => [
                ArtworkSubcategory::ThreeDRender,
                ArtworkSubcategory::Vector,
                ArtworkSubcategory::AIArt,
                ArtworkSubcategory::PixelArt,
            ],
            self::Sculpture => [
                ArtworkSubcategory::Metal,
                ArtworkSubcategory::Wood,
                ArtworkSubcategory::Resin,
                ArtworkSubcategory::Ceramic,
            ],
            self::Drawing => [
                ArtworkSubcategory::Graphite,
                ArtworkSubcategory::Charcoal,
            ],
            default => [],
        };
    }
}
