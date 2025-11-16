import PublicLayout from '../../layouts/PublicLayout';
import SEO from '../../components/SEO';

export default function TermsPage() {
  return (
    <PublicLayout>
      <SEO title="Kullanıcı Sözleşmesi" description="Alanya TEKMER kullanıcı sözleşmesi" />
      
      <div className="py-16">
        <div className="container-custom">
          <div className="max-w-4xl mx-auto prose prose-lg">
            <h1>Kullanıcı Sözleşmesi</h1>
            
            <p>
              Bu web sitesini kullanarak aşağıdaki şartları kabul etmiş sayılırsınız.
            </p>

            <h2>Genel Hükümler</h2>
            <p>
              Bu web sitesi Alanya TEKMER A.Ş. tarafından işletilmektedir. 
              Sitede yer alan tüm içerik ve bilgiler Alanya TEKMER'e aittir.
            </p>

            <h2>Kullanım Koşulları</h2>
            <ul>
              <li>Web sitesini yasal amaçlar için kullanacağınızı kabul edersiniz</li>
              <li>Başkalarının haklarını ihlal etmeyeceğinizi taahhüt edersiniz</li>
              <li>Yanlış veya yanıltıcı bilgi vermeyeceğinizi kabul edersiniz</li>
            </ul>

            <h2>Fikri Mülkiyet Hakları</h2>
            <p>
              Bu web sitesinde yer alan tüm içerik, tasarım, logo ve diğer materyaller 
              Alanya TEKMER'in mülkiyetindedir ve telif hakkı yasaları ile korunmaktadır.
            </p>

            <h2>Sorumluluk Sınırlaması</h2>
            <p>
              Alanya TEKMER, web sitesinde yer alan bilgilerin doğruluğu ve güncelliği 
              konusunda azami özeni göstermekle birlikte, herhangi bir garanti vermemektedir.
            </p>

            <h2>İletişim</h2>
            <p>
              Bu sözleşme ile ilgili sorularınız için destek@alanyatekmer.com 
              adresinden bizimle iletişime geçebilirsiniz.
            </p>
          </div>
        </div>
      </div>
    </PublicLayout>
  );
}

