import React, { useState } from 'react';
import { Search, Menu, ArrowRight, ChevronRight, Info, Type, X } from 'lucide-react';

export default function App() {
  const [showFontInfo, setShowFontInfo] = useState(false);

  return (
    <div className="min-h-screen bg-[#F9F8F6] text-[#1A1A1A] font-sans antialiased">
      {/* Navigasi Utama */}
      <nav className="sticky top-0 z-50 bg-[#F9F8F6]/80 backdrop-blur-md border-b border-black/5">
        <div className="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
          <div className="flex items-center gap-8">
            <a href="/" className="font-serif text-2xl font-bold tracking-tighter italic">Nurani</a>
            <div className="hidden md:flex items-center gap-6">
              <a href="#" className="font-mono text-[10px] uppercase tracking-widest text-[#8C8C8C] hover:text-black transition-colors">Terbaru</a>
              <a href="#" className="font-mono text-[10px] uppercase tracking-widest text-[#8C8C8C] hover:text-black transition-colors">Arsip</a>
              <a href="#" className="font-mono text-[10px] uppercase tracking-widest text-[#8C8C8C] hover:text-black transition-colors">Tentang</a>
            </div>
          </div>
          
          <div className="flex items-center gap-4">
            <button 
              onClick={() => setShowFontInfo(!showFontInfo)}
              className="flex items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-[#8C8C8C] hover:text-black transition-colors px-3 py-1 border border-black/10 rounded-full"
            >
              <Type size={14} />
              <span className="hidden sm:inline">Info Font</span>
            </button>
            <div className="relative hidden sm:block">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-black/30" size={16} />
              <input 
                type="text" 
                placeholder="Cari artikel..."
                className="pl-10 pr-4 py-1.5 bg-black/5 rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-black/10 w-48 transition-all focus:w-64"
              />
            </div>
            <button className="md:hidden p-2 hover:bg-black/5 rounded-full">
              <Menu size={20} />
            </button>
          </div>
        </div>
      </nav>

      {/* Panel Informasi Font */}
      {showFontInfo && (
        <div className="bg-black text-white py-8 border-b border-white/10 relative">
          <button 
            onClick={() => setShowFontInfo(false)}
            className="absolute top-4 right-6 text-white/40 hover:text-white"
          >
            <X size={20} />
          </button>
          <div className="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">
            <div>
              <h4 className="font-mono text-[10px] uppercase tracking-widest text-white/40 mb-4">Tipografi Utama (Serif)</h4>
              <p className="font-serif text-2xl mb-2">Libre Baskerville</p>
              <p className="text-sm text-white/50 leading-relaxed">
                Digunakan untuk judul besar dan teks naratif. Memberikan kesan klasik, elegan, dan mencerminkan kepercayaan serta kemurnian produk halal.
              </p>
            </div>
            <div>
              <h4 className="font-mono text-[10px] uppercase tracking-widest text-white/40 mb-4">Tipografi Antarmuka (Sans)</h4>
              <p className="font-sans text-2xl font-bold mb-2">Inter</p>
              <p className="text-sm text-white/50 leading-relaxed">
                Digunakan untuk teks tubuh dan elemen navigasi. Menjamin keterbacaan yang maksimal pada layar digital dengan estetika modern yang bersih.
              </p>
            </div>
            <div>
              <h4 className="font-mono text-[10px] uppercase tracking-widest text-white/40 mb-4">Tipografi Data (Mono)</h4>
              <p className="font-mono text-2xl mb-2">JetBrains Mono</p>
              <p className="text-sm text-white/50 leading-relaxed">
                Digunakan untuk metadata, label kategori, dan elemen teknis. Memberikan kesan presisi, kejujuran data, dan keteraturan informasi.
              </p>
            </div>
          </div>
        </div>
      )}

      <main>
        {/* SECTION 1: HERO EDITORIAL */}
        <section className="border-b border-black/5">
          <div className="max-w-7xl mx-auto px-6 py-24 md:py-40 grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            <div className="lg:col-span-7">
              <span className="font-mono text-[10px] uppercase tracking-widest text-[#8C8C8C] mb-8 block">Esai Unggulan — Vol. 01</span>
              <h2 className="font-serif text-6xl md:text-9xl font-bold leading-[0.82] tracking-tighter mb-10">
                Kemurnian <br />
                Tanpa <span className="italic">Kompromi.</span>
              </h2>
              <p className="font-serif italic text-xl md:text-2xl text-black/60 leading-relaxed mb-12 max-w-2xl">
                "Kecantikan sejati dimulai dari kejujuran bahan. Kami menelusuri standar baru dalam etika perawatan diri Muslimah modern."
              </p>
              <a href="#" className="inline-flex items-center gap-4 font-mono text-xs uppercase tracking-[0.2em] font-bold border-b-2 border-black pb-2 hover:gap-8 transition-all">
                Baca Manifesto <ArrowRight size={16} />
              </a>
            </div>
            <div className="lg:col-span-5 hidden lg:block border-l border-black/10 pl-16">
              <div className="space-y-16">
                <div className="relative">
                  <span className="absolute -left-[68px] top-0 font-mono text-[10px] text-black/20 rotate-180 [writing-mode:vertical-rl] tracking-widest">01 / 06</span>
                  <h3 className="font-serif text-3xl font-bold mb-4">Bahan Thoyyib</h3>
                  <p className="text-black/50 leading-relaxed">Lebih dari sekadar halal, kami mengutamakan bahan yang memberikan manfaat nyata bagi kulit.</p>
                </div>
                <div className="relative">
                  <span className="absolute -left-[68px] top-0 font-mono text-[10px] text-black/20 rotate-180 [writing-mode:vertical-rl] tracking-widest">02 / 06</span>
                  <h3 className="font-serif text-3xl font-bold mb-4">Etika Produksi</h3>
                  <p className="text-black/50 leading-relaxed">Memastikan setiap rantai pasokan bebas dari kekejaman dan menjunjung tinggi keadilan.</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* SECTION 2: GRID TERBARU */}
        <section className="bg-white border-b border-black/5">
          <div className="max-w-7xl mx-auto px-6 py-24">
            <div className="flex items-end justify-between mb-16">
              <div>
                <span className="font-mono text-[10px] uppercase tracking-widest text-[#8C8C8C] mb-2 block">Bagian 02</span>
                <h2 className="font-serif text-4xl font-bold italic">Perspektif Terbaru</h2>
              </div>
              <a href="#" className="font-mono text-[10px] uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-3 gap-px bg-black/5 border border-black/5">
              {[
                { cat: 'EDUKASI', title: 'Pentingnya Sertifikasi Halal dalam Kosmetik Modern', time: '5 MENIT' },
                { cat: 'TIPS', title: 'Bahan Alami vs Bahan Kimia: Mana yang Lebih Baik?', time: '8 MENIT' },
                { cat: 'GAYA HIDUP', title: 'Psikologi Kecantikan dan Kepercayaan Diri', time: '12 MENIT' }
              ].map((item, i) => (
                <div key={i} className="bg-white p-10 hover:bg-[#F9F8F6] transition-colors group cursor-pointer">
                  <span className="font-mono text-[10px] uppercase tracking-widest text-black/30 mb-6 block">{item.cat}</span>
                  <h3 className="font-serif text-2xl font-bold mb-6 leading-tight group-hover:text-black/60 transition-colors">{item.title}</h3>
                  <p className="text-sm text-black/50 leading-relaxed mb-10 line-clamp-3">
                    Menelusuri standar global kecantikan halal yang kini menjadi tren utama di berbagai belahan dunia...
                  </p>
                  <div className="flex items-center justify-between">
                    <span className="font-mono text-[10px] text-black/40">{item.time} BACA</span>
                    <ChevronRight size={16} className="text-black/20 group-hover:text-black transition-colors" />
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* SECTION 3: LIST TELAAH */}
        <section className="border-b border-black/5">
          <div className="max-w-4xl mx-auto px-6 py-24">
            <div className="text-center mb-24">
              <span className="font-mono text-[10px] uppercase tracking-widest text-[#8C8C8C] mb-4 block">Bagian 03</span>
              <h2 className="font-serif text-5xl font-bold">Telaah Mendalam</h2>
              <div className="w-16 h-1 bg-black mx-auto mt-8" />
            </div>

            <div className="space-y-32">
              {[
                { date: '28 FEB 2026', author: 'Rania Yusuf', title: 'Membangun Rutinitas Skincare yang Berkelanjutan' },
                { date: '25 FEB 2026', author: 'Zaskia Mecca', title: 'Masa Depan Pengetahuan Kecantikan Terdesentralisasi' }
              ].map((item, i) => (
                <article key={i} className="group cursor-pointer">
                  <div className="flex flex-col md:flex-row gap-12 md:gap-20">
                    <div className="md:w-40 flex-shrink-0">
                      <span className="font-mono text-[10px] uppercase tracking-widest block mb-4">{item.date}</span>
                      <div className="h-px w-10 bg-black/10 mb-6" />
                      <span className="font-mono text-[9px] text-black/30 uppercase block mb-2">PENULIS</span>
                      <span className="font-serif italic text-lg">{item.author}</span>
                    </div>
                    <div className="flex-grow">
                      <h3 className="font-serif text-4xl font-bold mb-6 group-hover:text-black/60 transition-colors leading-tight">{item.title}</h3>
                      <p className="font-sans text-lg text-black/60 leading-relaxed mb-10">
                        Panduan teknis memilih produk yang tidak hanya halal, tetapi juga ramah lingkungan dan bebas dari kekejaman terhadap hewan.
                      </p>
                      <div className="flex items-center gap-4 font-mono text-[10px] font-bold uppercase tracking-widest group-hover:gap-8 transition-all">
                        Baca Selengkapnya <ArrowRight size={14} />
                      </div>
                    </div>
                  </div>
                </article>
              ))}
            </div>
          </div>
        </section>

        {/* SECTION 4: KRONIK TIMELINE */}
        <section className="bg-[#F0EFEA] border-b border-black/5">
          <div className="max-w-5xl mx-auto px-6 py-24">
            <div className="mb-24">
              <span className="font-mono text-[10px] uppercase tracking-widest text-[#8C8C8C] mb-4 block">Bagian 04</span>
              <h2 className="font-serif text-6xl font-bold tracking-tighter">Kronik.</h2>
            </div>

            <div className="relative border-l border-black/10 ml-4 md:ml-40 pl-12 md:pl-20 space-y-32">
              {[
                { day: '05', month: 'MAR', title: 'Sertifikasi Halal Global' },
                { day: '02', month: 'MAR', title: 'Psikologi Perawatan Diri' },
                { day: '20', month: 'FEB', title: 'Etika Desain Produk' }
              ].map((item, i) => (
                <div key={i} className="relative group">
                  <div className="absolute -left-[54px] md:-left-[86px] top-2 w-4 h-4 rounded-full bg-black border-4 border-[#F0EFEA] z-10" />
                  <div className="hidden md:block absolute -left-56 top-0 w-40 text-right">
                    <span className="font-serif text-6xl font-bold text-black/5 leading-none block">{item.day}</span>
                    <span className="font-mono text-[10px] uppercase tracking-widest text-black/40 mt-2 block">{item.month} 2026</span>
                  </div>
                  <div className="max-w-2xl">
                    <span className="md:hidden font-mono text-[10px] mb-4 block">{item.day} {item.month} 2026</span>
                    <h3 className="font-serif text-4xl font-bold mb-6 group-hover:italic transition-all cursor-pointer leading-tight">{item.title}</h3>
                    <p className="text-black/50 leading-relaxed mb-8">
                      Menelusuri jejak perkembangan standar kecantikan yang menjunjung tinggi nilai-nilai kemanusiaan dan spiritualitas.
                    </p>
                    <div className="flex items-center gap-6 font-mono text-[10px] text-black/30 uppercase tracking-widest">
                      <span>Edukasi</span>
                      <div className="w-1 h-1 bg-black/20 rounded-full" />
                      <span>10 Menit</span>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* SECTION 5: FRAGMEN BRUTALIST */}
        <section className="bg-white border-b border-black/5">
          <div className="max-w-7xl mx-auto px-6 py-24">
            <div className="flex items-center gap-6 mb-20">
              <div className="w-10 h-10 bg-black text-white flex items-center justify-center font-mono text-sm font-bold">05</div>
              <h2 className="font-mono text-sm font-bold uppercase tracking-[0.4em]">Fragmen Eksperimental</h2>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-16">
              {[
                { id: '001', title: 'TEKNOLOGI_SKINCARE_ISLAMI', cat: 'TEKNO' },
                { id: '002', title: 'MINIMALISME_DALAM_RIASAN', cat: 'GAYA' }
              ].map((item, i) => (
                <div key={i} className="border-2 border-black p-10 bg-white shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] hover:shadow-[20px_20px_0px_0px_rgba(0,0,0,1)] hover:-translate-x-2 hover:-translate-y-2 transition-all cursor-pointer group">
                  <div className="flex justify-between items-start mb-10">
                    <span className="font-mono text-[10px] bg-black text-white px-3 py-1 uppercase tracking-widest">{item.cat}</span>
                    <span className="font-mono text-[10px] text-black/30">FRAGMENT_ID: {item.id}</span>
                  </div>
                  <h3 className="font-sans text-3xl font-black uppercase leading-[0.9] tracking-tighter mb-10 group-hover:text-black/60 transition-colors">{item.title}</h3>
                  <p className="font-mono text-xs text-black/60 uppercase leading-normal mb-12">
                    Eksplorasi mendalam tentang bagaimana nilai-nilai tradisional bertemu dengan inovasi modern dalam industri kecantikan global.
                  </p>
                  <div className="flex items-center gap-4 font-mono text-[10px] font-bold">
                    <span>AKSES_DATA</span>
                    <div className="h-px flex-grow bg-black/10" />
                    <ArrowRight size={14} />
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* SECTION 6: ARSIP DARK */}
        <section className="bg-[#1A1A1A] text-white">
          <div className="max-w-7xl mx-auto px-6 py-32">
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-24">
              <div className="lg:col-span-4">
                <span className="font-mono text-[10px] uppercase tracking-widest text-white/40 mb-6 block">Bagian 06</span>
                <h2 className="font-serif text-6xl font-bold italic mb-10">Arsip.</h2>
                <p className="text-white/40 text-sm leading-relaxed mb-12 max-w-sm">
                  Kumpulan lengkap pemikiran, catatan teknis, dan esai yang telah diterbitkan sejak awal perjalanan Nurani.
                </p>
                <div className="flex flex-wrap gap-3">
                  {['Skincare', 'Halal', 'Etika', 'Sains', 'Tips'].map(tag => (
                    <span key={tag} className="px-4 py-1.5 border border-white/10 rounded-full font-mono text-[10px] uppercase tracking-widest hover:bg-white hover:text-black transition-colors cursor-pointer">
                      {tag}
                    </span>
                  ))}
                </div>
              </div>
              <div className="lg:col-span-8">
                <div className="border-t border-white/10 divide-y divide-white/10">
                  {[
                    { date: '20 FEB', title: 'Desain Produk Berkelanjutan' },
                    { date: '15 FEB', title: 'Manfaat Puasa bagi Kulit' },
                    { date: '10 FEB', title: 'Memilih Sunscreen Halal' },
                    { date: '05 FEB', title: 'Etika Pengujian Produk' }
                  ].map((item, i) => (
                    <div key={i} className="py-8 flex items-center justify-between group cursor-pointer hover:bg-white/5 px-6 -mx-6 transition-colors">
                      <div className="flex items-center gap-12">
                        <span className="font-mono text-[10px] text-white/20 w-16">{item.date}</span>
                        <h4 className="font-sans text-lg font-medium group-hover:translate-x-4 transition-transform">{item.title}</h4>
                      </div>
                      <ArrowRight size={18} className="text-white/10 group-hover:text-white transition-colors" />
                    </div>
                  ))}
                </div>
                <button className="mt-16 w-full py-5 border border-white/10 font-mono text-[10px] uppercase tracking-[0.3em] hover:bg-white hover:text-black transition-all">
                  Lihat Seluruh Arsip (120+)
                </button>
              </div>
            </div>
          </div>
        </section>
      </main>

      {/* Footer */}
      <footer className="bg-white py-32 border-t border-black/5">
        <div className="max-w-7xl mx-auto px-6">
          <div className="grid grid-cols-1 md:grid-cols-12 gap-20">
            <div className="md:col-span-5">
              <h4 className="font-serif text-4xl font-bold italic mb-8">Nurani</h4>
              <p className="text-black/50 text-base leading-relaxed max-w-sm">
                Platform edukasi kecantikan halal yang mengedepankan kemurnian bahan, 
                etika produksi, dan estetika minimalis untuk Muslimah modern.
              </p>
            </div>
            <div className="md:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-12">
              <div>
                <h5 className="font-mono text-[10px] uppercase tracking-widest text-black/30 mb-8">Navigasi</h5>
                <ul className="space-y-4 text-sm font-medium">
                  <li><a href="#" className="hover:text-black/50 transition-colors">Esai</a></li>
                  <li><a href="#" className="hover:text-black/50 transition-colors">Tips</a></li>
                  <li><a href="#" className="hover:text-black/50 transition-colors">Arsip</a></li>
                </ul>
              </div>
              <div>
                <h5 className="font-mono text-[10px] uppercase tracking-widest text-black/30 mb-8">Informasi</h5>
                <ul className="space-y-4 text-sm font-medium">
                  <li><a href="#" className="hover:text-black/50 transition-colors">Tentang Kami</a></li>
                  <li><a href="#" className="hover:text-black/50 transition-colors">Privasi</a></li>
                  <li><a href="#" className="hover:text-black/50 transition-colors">Kontak</a></li>
                </ul>
              </div>
              <div className="col-span-2 sm:col-span-1">
                <h5 className="font-mono text-[10px] uppercase tracking-widest text-black/30 mb-8">Buletin</h5>
                <div className="flex flex-col gap-4">
                  <input type="email" placeholder="Email Anda" className="bg-black/5 px-4 py-3 text-sm rounded focus:outline-none" />
                  <button className="bg-black text-white py-3 font-mono text-[10px] uppercase tracking-widest font-bold">Berlangganan</button>
                </div>
              </div>
            </div>
          </div>
          <div className="mt-32 pt-10 border-t border-black/5 flex flex-col sm:flex-row justify-between items-center gap-6">
            <span className="font-mono text-[10px] text-black/30 uppercase tracking-widest">© 2026 NURANI MAGAZINE — EST. 2024</span>
            <div className="flex gap-8">
              <a href="#" className="font-mono text-[10px] uppercase tracking-widest hover:text-black/50">Twitter</a>
              <a href="#" className="font-mono text-[10px] uppercase tracking-widest hover:text-black/50">Instagram</a>
              <a href="#" className="font-mono text-[10px] uppercase tracking-widest hover:text-black/50">RSS</a>
            </div>
          </div>
        </div>
      </footer>
    </div>
  );
}
