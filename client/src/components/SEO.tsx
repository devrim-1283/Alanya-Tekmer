import { Helmet } from 'react-helmet-async';

interface SEOProps {
  title?: string;
  description?: string;
  keywords?: string;
  ogImage?: string;
  ogType?: string;
}

export default function SEO({
  title = 'Alanya TEKMER - Teknoloji ve Girişimciliğin Merkezi',
  description = 'Alanya TEKMER olarak ALANYA TEKMER ALKÜ Kestel Yerleşkesinde 1085 m2 alan üzerine inşa edilmiş olup firmalar için konforlu odalar sunmaktadır.',
  keywords = 'TEKMER, Alanya, girişimcilik, teknoloji, inovasyon, startup, ALKÜ, Alanya Alaaddin Keykubat Üniversitesi, KOSGEB',
  ogImage = '/logo.png',
  ogType = 'website',
}: SEOProps) {
  const fullTitle = title.includes('Alanya TEKMER') ? title : `${title} | Alanya TEKMER`;

  return (
    <Helmet>
      {/* Basic meta tags */}
      <title>{fullTitle}</title>
      <meta name="description" content={description} />
      <meta name="keywords" content={keywords} />

      {/* Open Graph / Facebook */}
      <meta property="og:type" content={ogType} />
      <meta property="og:title" content={fullTitle} />
      <meta property="og:description" content={description} />
      <meta property="og:image" content={ogImage} />

      {/* Twitter */}
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" content={fullTitle} />
      <meta name="twitter:description" content={description} />
      <meta name="twitter:image" content={ogImage} />

      {/* Additional meta tags */}
      <meta name="robots" content="index, follow" />
      <meta name="language" content="Turkish" />
      <meta name="revisit-after" content="7 days" />
      <meta name="author" content="Alanya TEKMER" />
    </Helmet>
  );
}

