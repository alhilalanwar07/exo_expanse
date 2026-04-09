export type MarketplaceThemeCategory = 'All' | 'Pernikahan' | 'Ulang Tahun' | 'Khitanan';

export type MarketplaceThemeItem = {
  id: string;
  title: string;
  subtitle: string;
  category: Exclude<MarketplaceThemeCategory, 'All'>;
  badge: 'FREE' | 'PREMIUM';
  imageUrl: string;
};

export const marketplaceThemeCategories: MarketplaceThemeCategory[] = [
  'All',
  'Pernikahan',
  'Ulang Tahun',
  'Khitanan',
];

export const marketplaceThemeItems: MarketplaceThemeItem[] = [
  {
    id: 'elegan-minimalis',
    title: 'Elegan Minimalis',
    subtitle: 'Wedding & Formal',
    category: 'Pernikahan',
    badge: 'FREE',
    imageUrl: 'https://picsum.photos/seed/exo-elegan/640/760',
  },
  {
    id: 'gold-marble',
    title: 'Gold Marble',
    subtitle: 'Engagement',
    category: 'Pernikahan',
    badge: 'PREMIUM',
    imageUrl: 'https://picsum.photos/seed/exo-gold/640/760',
  },
  {
    id: 'floral-dream',
    title: 'Floral Dream',
    subtitle: 'Birthday & Social',
    category: 'Ulang Tahun',
    badge: 'FREE',
    imageUrl: 'https://picsum.photos/seed/exo-floral/640/760',
  },
  {
    id: 'midnight-gala',
    title: 'Midnight Gala',
    subtitle: 'Formal',
    category: 'Pernikahan',
    badge: 'PREMIUM',
    imageUrl: 'https://picsum.photos/seed/exo-gala/640/760',
  },
  {
    id: 'modern-party',
    title: 'Modern Party',
    subtitle: 'Ulang Tahun',
    category: 'Ulang Tahun',
    badge: 'FREE',
    imageUrl: 'https://picsum.photos/seed/exo-party/640/760',
  },
  {
    id: 'golden-khitan',
    title: 'Golden Khitan',
    subtitle: 'Khitanan',
    category: 'Khitanan',
    badge: 'PREMIUM',
    imageUrl: 'https://picsum.photos/seed/exo-khitan/640/760',
  },
];
