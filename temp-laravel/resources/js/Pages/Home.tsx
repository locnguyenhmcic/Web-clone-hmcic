export default function Home() {
    return (
        <main className="min-h-screen bg-slate-50 text-slate-900">
            <section className="mx-auto flex max-w-4xl flex-col gap-6 px-6 py-16">
                <p className="inline-block w-fit rounded-full border border-slate-300 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">
                    HMCIC CMS
                </p>
                <h1 className="text-4xl font-bold leading-tight sm:text-5xl">
                    Project Foundation is ready
                </h1>
                <p className="max-w-2xl text-lg text-slate-600">
                    Laravel, Filament, Inertia, React, TypeScript, Vite, and Tailwind are configured.
                </p>
            </section>
        </main>
    );
}
