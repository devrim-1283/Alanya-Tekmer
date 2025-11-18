<?php
$pageTitle = 'Hizmetlerimiz - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER hizmetleri ve KOSGEB destekleri hakkında bilgi alın.';
$currentPage = 'services';

logPageView('services');

include __DIR__ . '/../includes/header.php';
?>

<!-- Hero Header -->
<section class="page-hero" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); padding: 100px 0 80px; color: white; text-align: center;">
    <div class="container">
        <div data-aos="fade-up">
            <span style="display: inline-block; background: rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 50px; font-size: 0.9em; margin-bottom: 20px;">Neler Sunuyoruz?</span>
            <h1 style="font-size: 3em; font-weight: 800; margin-bottom: 20px; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">Hizmetlerimiz</h1>
            <p style="font-size: 1.3em; max-width: 700px; margin: 0 auto; opacity: 0.95;">ALANYA TEKMER'in Sizlere Sağlayacağı Avantajlar</p>
        </div>
    </div>
</section>

<!-- Services Intro -->
<section style="padding: 80px 0; background: #f9fafb;">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto; text-align: center;" data-aos="fade-up">
            <p style="font-size: 1.3em; line-height: 1.8; color: #374151; margin-bottom: 20px;">
                <strong>ALANYA TEKMER</strong> girişimcilere ve işletmelere ön inkübasyon, inkübasyon ve inkübasyon sonrası süreçlerde; iş geliştirme, mali kaynaklara erişim, yönetim, danışmanlık, mentörlük, ofis ve ağlara katılım gibi hizmetler ile destek olmak amaçlı kurulmuş bir teknoloji merkezidir.
            </p>
            <p style="font-size: 1.1em; color: #6b7280;">
                TEKMER'de yer alan girişimcilerimiz ve işletmelerimiz devlet tarafından sağlanan çeşitli teşvik ve muafiyetlerden de yararlanabilecektir.
            </p>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section style="padding: 80px 0; background: white;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 60px;" data-aos="fade-up">
            <span style="display: inline-block; background: #dbeafe; color: #1e40af; padding: 8px 20px; border-radius: 50px; font-size: 0.9em; font-weight: 600; margin-bottom: 15px;">Kapsamlı Destek</span>
            <h2 style="font-size: 2.5em; font-weight: 800; color: #111827; margin-bottom: 15px;">Sunduğumuz Hizmetler</h2>
            <p style="color: #6b7280; font-size: 1.1em; max-width: 600px; margin: 0 auto;">Girişimcilere ve şirketlere yönelik profesyonel destek hizmetleri</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <div style="background: white; border-radius: 16px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 2px solid #f3f4f6; transition: all 0.3s ease;" data-aos="fade-up" data-aos-delay="100" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='#f3f4f6'; this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-file-invoice" style="font-size: 24px; color: white;"></i>
                </div>
                <h3 style="font-size: 1.4em; font-weight: 700; color: #111827; margin-bottom: 12px;">KOSGEB Tekmer Muafiyetleri</h3>
                <p style="color: #6b7280; line-height: 1.7;">5746 Sayılı Kanun kapsamında çeşitli vergi ve harç muafiyetlerinden yararlanma imkanı.</p>
            </div>
            
            <div style="background: white; border-radius: 16px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 2px solid #f3f4f6; transition: all 0.3s ease;" data-aos="fade-up" data-aos-delay="150" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='#f3f4f6'; this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-user-tie" style="font-size: 24px; color: white;"></i>
                </div>
                <h3 style="font-size: 1.4em; font-weight: 700; color: #111827; margin-bottom: 12px;">Danışmanlık & Mentörlük</h3>
                <p style="color: #6b7280; line-height: 1.7;">Deneyimli mentörler ve uzmanlardan iş geliştirme, strateji ve yönetim konularında destek.</p>
            </div>
            
            <div style="background: white; border-radius: 16px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 2px solid #f3f4f6; transition: all 0.3s ease;" data-aos="fade-up" data-aos-delay="200" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='#f3f4f6'; this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-certificate" style="font-size: 24px; color: white;"></i>
                </div>
                <h3 style="font-size: 1.4em; font-weight: 700; color: #111827; margin-bottom: 12px;">Sınai Mülkiyet Danışmanlık</h3>
                <p style="color: #6b7280; line-height: 1.7;">Patent, faydalı model, marka ve tasarım tescili konularında profesyonel danışmanlık.</p>
            </div>
            
            <div style="background: white; border-radius: 16px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 2px solid #f3f4f6; transition: all 0.3s ease;" data-aos="fade-up" data-aos-delay="250" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='#f3f4f6'; this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-graduation-cap" style="font-size: 24px; color: white;"></i>
                </div>
                <h3 style="font-size: 1.4em; font-weight: 700; color: #111827; margin-bottom: 12px;">Eğitimler</h3>
                <p style="color: #6b7280; line-height: 1.7;">Girişimcilik, iş geliştirme, pazarlama ve teknoloji konularında düzenli eğitimler.</p>
            </div>
            
            <div style="background: white; border-radius: 16px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 2px solid #f3f4f6; transition: all 0.3s ease;" data-aos="fade-up" data-aos-delay="300" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='#f3f4f6'; this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ec4899, #db2777); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-coffee" style="font-size: 24px; color: white;"></i>
                </div>
                <h3 style="font-size: 1.4em; font-weight: 700; color: #111827; margin-bottom: 12px;">Sosyal Alanlar</h3>
                <p style="color: #6b7280; line-height: 1.7;">Rahat ve konforlu sosyal alanlar ile networking imkanları.</p>
            </div>
            
            <div style="background: white; border-radius: 16px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 2px solid #f3f4f6; transition: all 0.3s ease;" data-aos="fade-up" data-aos-delay="350" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='#f3f4f6'; this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #06b6d4, #0891b2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-shield-alt" style="font-size: 24px; color: white;"></i>
                </div>
                <h3 style="font-size: 1.4em; font-weight: 700; color: #111827; margin-bottom: 12px;">Temizlik ve Güvenlik</h3>
                <p style="color: #6b7280; line-height: 1.7;">Profesyonel temizlik ve 7/24 güvenlik hizmetleri.</p>
            </div>
            
            <div style="background: white; border-radius: 16px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 2px solid #f3f4f6; transition: all 0.3s ease;" data-aos="fade-up" data-aos-delay="400" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='#f3f4f6'; this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #14b8a6, #0d9488); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-handshake" style="font-size: 24px; color: white;"></i>
                </div>
                <h3 style="font-size: 1.4em; font-weight: 700; color: #111827; margin-bottom: 12px;">Toplantı Salonu</h3>
                <p style="color: #6b7280; line-height: 1.7;">Profesyonel toplantılar için tam donanımlı toplantı salonu.</p>
            </div>
            
            <div style="background: white; border-radius: 16px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 2px solid #f3f4f6; transition: all 0.3s ease;" data-aos="fade-up" data-aos-delay="450" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='#f3f4f6'; this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #6366f1, #4f46e5); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-university" style="font-size: 24px; color: white;"></i>
                </div>
                <h3 style="font-size: 1.4em; font-weight: 700; color: #111827; margin-bottom: 12px;">Kampüs Olanakları</h3>
                <p style="color: #6b7280; line-height: 1.7;">ALKÜ kampüsü içerisinde olmanın tüm avantajlarından yararlanma.</p>
            </div>
            
            <div style="background: white; border-radius: 16px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 2px solid #f3f4f6; transition: all 0.3s ease;" data-aos="fade-up" data-aos-delay="500" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='#f3f4f6'; this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #84cc16, #65a30d); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-wifi" style="font-size: 24px; color: white;"></i>
                </div>
                <h3 style="font-size: 1.4em; font-weight: 700; color: #111827; margin-bottom: 12px;">Ücretsiz Wi-Fi</h3>
                <p style="color: #6b7280; line-height: 1.7;">Yüksek hızlı ve güvenli internet erişimi.</p>
            </div>
            
            <div style="background: white; border-radius: 16px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 2px solid #f3f4f6; transition: all 0.3s ease;" data-aos="fade-up" data-aos-delay="550" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='#f3f4f6'; this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-clock" style="font-size: 24px; color: white;"></i>
                </div>
                <h3 style="font-size: 1.4em; font-weight: 700; color: #111827; margin-bottom: 12px;">7/24 Çalışma İmkanı</h3>
                <p style="color: #6b7280; line-height: 1.7;">Haftanın 7 günü, günün 24 saati çalışma imkanı.</p>
            </div>
        </div>
    </div>
</section>

<!-- KOSGEB Benefits Section -->
<section style="padding: 100px 0; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
    <div class="container">
        <div style="text-align: center; margin-bottom: 60px;" data-aos="fade-up">
            <span style="display: inline-block; background: #3b82f6; color: white; padding: 8px 20px; border-radius: 50px; font-size: 0.9em; font-weight: 600; margin-bottom: 15px;">Devlet Destekleri</span>
            <h2 style="font-size: 2.8em; font-weight: 800; color: #111827; margin-bottom: 20px;">KOSGEB Teşvik ve Muafiyetleri</h2>
            <p style="color: #374151; font-size: 1.2em; max-width: 900px; margin: 0 auto; line-height: 1.8;">
                KOSGEB tarafından desteklenen ve <strong>5746 sayılı Kanun'un</strong> tanıdığı muafiyetlerden yararlanabilen teknoloji merkezi (TEKMER)'nde yer alan teknoloji firmaları, çeşitli teşvik ve istisnalardan faydalanabilir:
            </p>
        </div>
        
        <div style="max-width: 1100px; margin: 0 auto;">
            <div style="background: white; border-radius: 20px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 25px; border-left: 5px solid #10b981; transition: transform 0.3s ease;" data-aos="fade-up" data-aos-delay="100" onmouseover="this.style.transform='translateX(10px)'" onmouseout="this.style.transform='translateX(0)'">
                <div style="display: flex; gap: 20px; align-items: flex-start;">
                    <div style="flex-shrink: 0; width: 50px; height: 50px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-percentage" style="font-size: 22px; color: white;"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.5em; font-weight: 700; color: #111827; margin-bottom: 12px;">Ar-Ge ve Tasarım İndirimi</h3>
                        <p style="color: #6b7280; line-height: 1.8; font-size: 1.05em;">Ar-Ge ve yenilik veya tasarım harcamalarının tamamı (<strong style="color: #10b981;">%100'ü</strong>), kurum kazancının tespitinde indirim konusu yapılmaktadır.</p>
                    </div>
                </div>
            </div>
            
            <div style="background: white; border-radius: 20px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 25px; border-left: 5px solid #3b82f6; transition: transform 0.3s ease;" data-aos="fade-up" data-aos-delay="150" onmouseover="this.style.transform='translateX(10px)'" onmouseout="this.style.transform='translateX(0)'">
                <div style="display: flex; gap: 20px; align-items: flex-start;">
                    <div style="flex-shrink: 0; width: 50px; height: 50px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-hand-holding-usd" style="font-size: 22px; color: white;"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.5em; font-weight: 700; color: #111827; margin-bottom: 12px;">Gelir Vergi Stopajı Teşviki</h3>
                        <p style="color: #6b7280; line-height: 1.8; font-size: 1.05em;">Teknoloji merkezlerinde çalışan Ar-Ge ve destek personelinin elde ettikleri ücretler üzerinden hesaplanan gelir vergisinin belirli oranları vergiden indirilebilir.</p>
                    </div>
                </div>
            </div>
            
            <div style="background: white; border-radius: 20px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 25px; border-left: 5px solid #f59e0b; transition: transform 0.3s ease;" data-aos="fade-up" data-aos-delay="200" onmouseover="this.style.transform='translateX(10px)'" onmouseout="this.style.transform='translateX(0)'">
                <div style="display: flex; gap: 20px; align-items: flex-start;">
                    <div style="flex-shrink: 0; width: 50px; height: 50px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-shield-alt" style="font-size: 22px; color: white;"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.5em; font-weight: 700; color: #111827; margin-bottom: 12px;">Sigorta Primi Desteği</h3>
                        <p style="color: #6b7280; line-height: 1.8; font-size: 1.05em;">Teknoloji merkezlerinde çalışan Ar-Ge ve destek personelinin elde ettikleri ücretler üzerinden hesaplanan sigorta primi işveren hissesinin <strong style="color: #f59e0b;">%50'si</strong> karşılanmaktadır.</p>
                    </div>
                </div>
            </div>
            
            <div style="background: white; border-radius: 20px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 25px; border-left: 5px solid #8b5cf6; transition: transform 0.3s ease;" data-aos="fade-up" data-aos-delay="250" onmouseover="this.style.transform='translateX(10px)'" onmouseout="this.style.transform='translateX(0)'">
                <div style="display: flex; gap: 20px; align-items: flex-start;">
                    <div style="flex-shrink: 0; width: 50px; height: 50px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-stamp" style="font-size: 22px; color: white;"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.5em; font-weight: 700; color: #111827; margin-bottom: 12px;">Damga Vergisi İstisnası</h3>
                        <p style="color: #6b7280; line-height: 1.8; font-size: 1.05em;">Ar-Ge ve yenilik faaliyetleri ile ilgili olarak düzenlenen kağıtlar damga vergisinden istisnadır.</p>
                    </div>
                </div>
            </div>
            
            <div style="background: white; border-radius: 20px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 25px; border-left: 5px solid #ec4899; transition: transform 0.3s ease;" data-aos="fade-up" data-aos-delay="300" onmouseover="this.style.transform='translateX(10px)'" onmouseout="this.style.transform='translateX(0)'">
                <div style="display: flex; gap: 20px; align-items: flex-start;">
                    <div style="flex-shrink: 0; width: 50px; height: 50px; background: linear-gradient(135deg, #ec4899, #db2777); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-globe" style="font-size: 22px; color: white;"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.5em; font-weight: 700; color: #111827; margin-bottom: 12px;">Gümrük Vergisi İstisnası</h3>
                        <p style="color: #6b7280; line-height: 1.8; font-size: 1.05em;">Ar-Ge, yenilik ve tasarım projeleri ile ilgili araştırmalarda kullanılmak üzere ithal edilen eşya gümrük vergisinden ve diğer harçlardan istisnadır.</p>
                    </div>
                </div>
            </div>
            
            <div style="background: white; border-radius: 20px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 40px; border-left: 5px solid #06b6d4; transition: transform 0.3s ease;" data-aos="fade-up" data-aos-delay="350" onmouseover="this.style.transform='translateX(10px)'" onmouseout="this.style.transform='translateX(0)'">
                <div style="display: flex; gap: 20px; align-items: flex-start;">
                    <div style="flex-shrink: 0; width: 50px; height: 50px; background: linear-gradient(135deg, #06b6d4, #0891b2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-graduate" style="font-size: 22px; color: white;"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.5em; font-weight: 700; color: #111827; margin-bottom: 12px;">Temel Bilimler Desteği</h3>
                        <p style="color: #6b7280; line-height: 1.8; font-size: 1.05em;">En az lisans derecesine sahip Ar-Ge personeli istihdam eden teknoloji merkezlerine, bu personelin aylık ücretinin o yıl için uygulanan asgari ücretin brüt tutarı kadarlık kısmı, Sanayi ve Teknoloji Bakanlığı bütçesinden karşılanır.</p>
                    </div>
                </div>
            </div>
            
            <div style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border-radius: 16px; padding: 35px; border: 2px solid #93c5fd; text-align: center;" data-aos="fade-up" data-aos-delay="400">
                <i class="fas fa-info-circle" style="font-size: 2.5em; color: #3b82f6; margin-bottom: 15px;"></i>
                <p style="color: #1e40af; font-size: 1.15em; line-height: 1.8; margin: 0;">
                    <strong>Bu destekler ve istisnalar,</strong> teknoloji merkezlerinin Ar-Ge, yenilik ve tasarım faaliyetlerini teşvik etmek ve desteklemek amacıyla sağlanmaktadır.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section style="padding: 100px 0; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #2563eb 100%); position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1; background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;" data-aos="zoom-in">
            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px;">
                <i class="fas fa-rocket" style="font-size: 35px; color: white;"></i>
            </div>
            <h2 style="font-size: 2.8em; font-weight: 800; color: white; margin-bottom: 20px; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                Bu Avantajlardan Yararlanmak İster misiniz?
            </h2>
            <p style="font-size: 1.3em; color: rgba(255,255,255,0.95); margin-bottom: 40px; line-height: 1.7;">
                Hemen başvurun, <strong>Alanya TEKMER</strong> ailesine katılın ve tüm bu desteklerden yararlanmaya başlayın!
            </p>
            <a href="<?php echo url('basvuru'); ?>" style="display: inline-block; background: white; color: #1e40af; padding: 18px 45px; border-radius: 50px; font-size: 1.2em; font-weight: 700; text-decoration: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 40px rgba(0,0,0,0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.2)'">
                <i class="fas fa-file-alt"></i> Hemen Başvuru Yap
            </a>
            <p style="margin-top: 25px; color: rgba(255,255,255,0.8); font-size: 0.95em;">
                <i class="fas fa-check-circle"></i> Ücretsiz Başvuru &nbsp;|&nbsp; <i class="fas fa-clock"></i> Hızlı Değerlendirme &nbsp;|&nbsp; <i class="fas fa-headset"></i> 7/24 Destek
            </p>
        </div>
    </div>
</section>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>

