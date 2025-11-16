import { useState } from 'react';
import { useForm } from 'react-hook-form';
import { useMutation, useQuery } from '@tanstack/react-query';
import { publicApi } from '../../lib/api';
import PublicLayout from '../../layouts/PublicLayout';
import SEO from '../../components/SEO';

export default function ApplicationPage() {
  const [step, setStep] = useState(1);
  const [submitted, setSubmitted] = useState(false);
  const [projectFile, setProjectFile] = useState<File | null>(null);

  const { register, handleSubmit, formState: { errors } } = useForm<any>({
    mode: 'onChange',
  });

  // Fetch combobox options
  const { data: projectTypes } = useQuery({
    queryKey: ['combobox', 'project_type'],
    queryFn: async () => {
      const response = await publicApi.getComboboxOptions('project_type');
      return response.data.data;
    },
  });

  const { data: businessIdeas } = useQuery({
    queryKey: ['combobox', 'business_idea'],
    queryFn: async () => {
      const response = await publicApi.getComboboxOptions('business_idea');
      return response.data.data;
    },
  });

  const { data: requestedAreas } = useQuery({
    queryKey: ['combobox', 'requested_area'],
    queryFn: async () => {
      const response = await publicApi.getComboboxOptions('requested_area');
      return response.data.data;
    },
  });

  const mutation = useMutation({
    mutationFn: async (data: any) => {
      const formData = new FormData();
      
      // Add all form fields
      Object.entries(data).forEach(([key, value]) => {
        if (value !== undefined && value !== '' && key !== 'data_consent') {
          formData.append(key, String(value));
        }
      });
      
      // Add project file
      if (projectFile) {
        formData.append('project_file', projectFile);
      }
      
      // Add turnstile token (dummy for now)
      formData.append('turnstileToken', 'dummy-token');
      
      return await publicApi.submitApplication(formData);
    },
    onSuccess: () => {
      setSubmitted(true);
    },
  });

  const onSubmit = (data: any) => {
    mutation.mutate(data);
  };

  const nextStep = () => setStep(step + 1);
  const prevStep = () => setStep(step - 1);

  if (submitted) {
    return (
      <PublicLayout>
        <SEO title="Başvuru Gönderildi" />
        <div className="py-16">
          <div className="container-custom max-w-2xl mx-auto text-center">
            <div className="card">
              <div className="text-6xl mb-4">✅</div>
              <h1 className="text-3xl font-bold mb-4">Başvurunuz Alındı!</h1>
              <p className="text-lg text-gray-600 mb-6">
                Başvurunuz başarıyla alınmıştır. İcra Kurulumuz projenizi detaylı inceleyecektir. 
                Değerlendirme sonucu en kısa sürede e-posta adresinize bildirilecektir.
              </p>
              <a href="/" className="btn btn-primary">Ana Sayfaya Dön</a>
            </div>
          </div>
        </div>
      </PublicLayout>
    );
  }

  return (
    <PublicLayout>
      <SEO title="Proje Başvurusu" description="Alanya TEKMER'e proje başvurusu yapın" />
      
      <div className="py-16">
        <div className="container-custom max-w-4xl mx-auto">
          <h1 className="text-4xl font-bold text-center mb-8">Proje Başvurusu</h1>
          
          {/* Progress bar */}
          <div className="mb-8">
            <div className="flex justify-between mb-2">
              {[1, 2, 3, 4].map((s) => (
                <div key={s} className={`flex-1 text-center ${s <= step ? 'text-primary-600 font-bold' : 'text-gray-400'}`}>
                  Adım {s}
                </div>
              ))}
            </div>
            <div className="h-2 bg-gray-200 rounded-full">
              <div 
                className="h-full bg-primary-600 rounded-full transition-all duration-300"
                style={{ width: `${(step / 4) * 100}%` }}
              />
            </div>
          </div>

          <form onSubmit={handleSubmit(onSubmit)} className="card">
            {/* Step 1: Proje Bilgileri */}
            {step === 1 && (
              <div className="space-y-4">
                <h2 className="text-2xl font-bold mb-4">Proje Bilgileri</h2>
                
                <div>
                  <label className="block mb-2 font-medium">Başvurulan Proje *</label>
                  <select {...register('project_type')} className="input">
                    <option value="">Seçiniz</option>
                    {projectTypes?.map((opt: any) => (
                      <option key={opt.id} value={opt.option_value}>{opt.option_value}</option>
                    ))}
                  </select>
                  {errors.project_type && <p className="text-red-500 text-sm mt-1">{String(errors.project_type.message)}</p>}
                </div>

                <div>
                  <label className="block mb-2 font-medium">İş Fikri / Faaliyet Alanı *</label>
                  <select {...register('business_idea')} className="input">
                    <option value="">Seçiniz</option>
                    {businessIdeas?.map((opt: any) => (
                      <option key={opt.id} value={opt.option_value}>{opt.option_value}</option>
                    ))}
                  </select>
                  {errors.business_idea && <p className="text-red-500 text-sm mt-1">{String(errors.business_idea.message)}</p>}
                </div>

                <div>
                  <label className="block mb-2 font-medium">Proje Adı *</label>
                  <input {...register('project_name')} className="input" />
                  {errors.project_name && <p className="text-red-500 text-sm mt-1">{String(errors.project_name.message)}</p>}
                </div>

                <div>
                  <label className="block mb-2 font-medium">Proje Ekibi Kaç Kişiden Oluşuyor? *</label>
                  <input {...register('team_size', { valueAsNumber: true })} type="number" className="input" />
                  {errors.team_size && <p className="text-red-500 text-sm mt-1">{String(errors.team_size.message)}</p>}
                </div>

                <div>
                  <label className="block mb-2 font-medium">Proje Özeti * (En az 50 karakter)</label>
                  <textarea {...register('project_summary')} rows={5} className="input" />
                  {errors.project_summary && <p className="text-red-500 text-sm mt-1">{String(errors.project_summary.message)}</p>}
                </div>

                <button type="button" onClick={nextStep} className="btn btn-primary w-full">
                  Sonraki Adım →
                </button>
              </div>
            )}

            {/* Step 2: Kişisel Bilgiler */}
            {step === 2 && (
              <div className="space-y-4">
                <h2 className="text-2xl font-bold mb-4">Kişisel Bilgiler</h2>
                
                <div>
                  <label className="block mb-2 font-medium">Ad Soyad *</label>
                  <input {...register('full_name')} className="input" />
                  {errors.full_name && <p className="text-red-500 text-sm mt-1">{String(errors.full_name.message)}</p>}
                </div>

                <div>
                  <label className="block mb-2 font-medium">TC Kimlik No * (11 hane)</label>
                  <input {...register('tc_no')} maxLength={11} className="input" />
                  {errors.tc_no && <p className="text-red-500 text-sm mt-1">{String(errors.tc_no.message)}</p>}
                </div>

                <div>
                  <label className="block mb-2 font-medium">Telefon * (örn: 5386912283)</label>
                  <input {...register('phone')} className="input" placeholder="5386912283" />
                  {errors.phone && <p className="text-red-500 text-sm mt-1">{String(errors.phone.message)}</p>}
                </div>

                <div>
                  <label className="block mb-2 font-medium">E-posta *</label>
                  <input {...register('email')} type="email" className="input" />
                  {errors.email && <p className="text-red-500 text-sm mt-1">{String(errors.email.message)}</p>}
                </div>

                <div>
                  <label className="block mb-2 font-medium">Üniversite / Bölüm</label>
                  <input {...register('university')} className="input" />
                </div>

                <div>
                  <label className="block mb-2 font-medium">Firma Adı (Varsa)</label>
                  <input {...register('company_name')} className="input" />
                </div>

                <div className="flex gap-4">
                  <button type="button" onClick={prevStep} className="btn btn-secondary flex-1">
                    ← Geri
                  </button>
                  <button type="button" onClick={nextStep} className="btn btn-primary flex-1">
                    Sonraki Adım →
                  </button>
                </div>
              </div>
            )}

            {/* Step 3: Talep ve Beklentiler */}
            {step === 3 && (
              <div className="space-y-4">
                <h2 className="text-2xl font-bold mb-4">Talep ve Beklentiler</h2>
                
                <div>
                  <label className="block mb-2 font-medium">Talep Edilen Alan *</label>
                  <select {...register('requested_area')} className="input">
                    <option value="">Seçiniz</option>
                    {requestedAreas?.map((opt: any) => (
                      <option key={opt.id} value={opt.option_value}>{opt.option_value}</option>
                    ))}
                  </select>
                  {errors.requested_area && <p className="text-red-500 text-sm mt-1">{String(errors.requested_area.message)}</p>}
                </div>

                <div>
                  <label className="block mb-2 font-medium">Alanya TEKMER'den Beklentileriniz * (En az 20 karakter)</label>
                  <textarea {...register('expectations')} rows={5} className="input" />
                  {errors.expectations && <p className="text-red-500 text-sm mt-1">{String(errors.expectations.message)}</p>}
                </div>

                <div className="flex gap-4">
                  <button type="button" onClick={prevStep} className="btn btn-secondary flex-1">
                    ← Geri
                  </button>
                  <button type="button" onClick={nextStep} className="btn btn-primary flex-1">
                    Sonraki Adım →
                  </button>
                </div>
              </div>
            )}

            {/* Step 4: Dosya ve Onay */}
            {step === 4 && (
              <div className="space-y-4">
                <h2 className="text-2xl font-bold mb-4">Dosya ve Onay</h2>
                
                <div>
                  <label className="block mb-2 font-medium">Proje Dosyası (PDF) *</label>
                  <input 
                    type="file" 
                    accept=".pdf" 
                    className="input"
                    onChange={(e) => {
                      const file = e.target.files?.[0];
                      if (file) {
                        setProjectFile(file);
                      }
                    }}
                    required
                  />
                  <p className="text-sm text-gray-500 mt-1">Maksimum dosya boyutu: 10MB</p>
                </div>

                <div className="bg-gray-50 p-4 rounded-lg">
                  <label className="flex items-start gap-3">
                    <input type="checkbox" className="mt-1" required />
                    <span className="text-sm">
                      Verdiğim tüm bilgiler doğru ve bana aittir. Bu bilgiler başvurumun değerlendirilmesi ve bana ulaşılmasında kullanılabilir. 
                      <a href="/gizlilik-sozlesmesi" target="_blank" className="text-primary-600 hover:underline"> KVKK BİLGİLENDİRME</a> metnini okudum, anladım ve kabul ediyorum. *
                    </span>
                  </label>
                </div>

                <div className="flex gap-4">
                  <button type="button" onClick={prevStep} className="btn btn-secondary flex-1">
                    ← Geri
                  </button>
                  <button 
                    type="submit" 
                    disabled={mutation.isPending}
                    className="btn btn-primary flex-1"
                  >
                    {mutation.isPending ? 'Gönderiliyor...' : 'Başvuruyu Gönder'}
                  </button>
                </div>

                {mutation.isError && (
                  <div className="bg-red-50 text-red-600 p-4 rounded-lg">
                    Başvuru gönderilirken bir hata oluştu. Lütfen tekrar deneyin.
                  </div>
                )}
              </div>
            )}
          </form>
        </div>
      </div>
    </PublicLayout>
  );
}

