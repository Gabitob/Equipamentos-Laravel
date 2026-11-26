<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    equipmentUrl: string;
    peopleUrl: string;
}>();
</script>

<template>
    <Head title="APG/TIC | Gestão de equipamentos" />

    <div class="min-h-screen bg-slate-50 text-slate-900">
        <header class="border-b border-slate-200 bg-white">
            <nav
                class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-5 lg:px-8"
                aria-label="Navegação principal"
            >
                <a href="/" class="flex items-center gap-3">
                    <span
                        class="flex size-11 items-center justify-center rounded-xl bg-sky-100 text-lg font-bold text-sky-900"
                        aria-hidden="true"
                    >
                        BA
                    </span>
                    <span class="leading-tight">
                        <span
                            class="block text-sm font-semibold text-slate-900"
                        >
                            Governo da Bahia
                        </span>
                        <span class="block text-xs text-slate-500"
                            >APG/TIC</span
                        >
                    </span>
                </a>

                <div class="flex items-center gap-3">
                    <Link
                        :href="peopleUrl"
                        class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 sm:inline-flex"
                    >
                        Pessoas
                    </Link>
                    <Link
                        :href="equipmentUrl"
                        class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 sm:inline-flex"
                    >
                        Equipamentos
                    </Link>
                    <Link
                        :href="equipmentUrl"
                        class="inline-flex items-center rounded-lg bg-sky-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-900"
                    >
                        Acessar sistema
                    </Link>
                </div>
            </nav>
        </header>

        <main>
            <section class="relative isolate overflow-hidden bg-white">
                <div
                    class="absolute -top-32 -right-24 -z-10 size-96 rounded-full bg-amber-100/70 blur-3xl"
                    aria-hidden="true"
                />
                <div
                    class="absolute -bottom-40 -left-20 -z-10 size-96 rounded-full bg-emerald-100/70 blur-3xl"
                    aria-hidden="true"
                />

                <div
                    class="mx-auto grid max-w-7xl items-center gap-14 px-6 py-20 sm:py-28 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:py-32"
                >
                    <div class="max-w-2xl">
                        <p
                            class="mb-6 inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3.5 py-1.5 text-xs font-semibold tracking-wide text-sky-900 uppercase"
                        >
                            <span
                                class="size-2 rounded-full bg-emerald-500"
                                aria-hidden="true"
                            />
                            APG/TIC · Governo da Bahia
                        </p>
                        <h1
                            class="text-4xl leading-tight font-semibold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl"
                        >
                            Gestão de equipamentos
                            <span class="text-sky-900"
                                >com clareza e confiança.</span
                            >
                        </h1>
                        <p
                            class="mt-6 max-w-xl text-lg leading-8 text-slate-600"
                        >
                            Um espaço institucional para registrar equipamentos,
                            acompanhar seu estado atual e consultar quem é
                            responsável por cada item.
                        </p>
                        <div
                            class="mt-9 flex flex-col gap-3 sm:flex-row sm:items-center"
                        >
                            <Link
                                :href="equipmentUrl"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-sky-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-900"
                            >
                                Acessar equipamentos
                                <span aria-hidden="true">→</span>
                            </Link>
                            <Link
                                :href="peopleUrl"
                                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-900"
                            >
                                Consultar pessoas
                            </Link>
                        </div>
                        <p class="mt-5 text-sm text-slate-500">
                            Acesso aos cadastros disponível para usuários
                            autenticados.
                        </p>
                    </div>

                    <div class="relative mx-auto w-full max-w-lg">
                        <div
                            class="absolute -inset-4 -rotate-2 rounded-3xl bg-gradient-to-br from-sky-100 via-rose-100 to-amber-100"
                            aria-hidden="true"
                        />
                        <div
                            class="relative overflow-hidden rounded-2xl border border-white/80 bg-white p-7 shadow-xl shadow-slate-900/10 sm:p-9"
                        >
                            <div class="flex items-start justify-between gap-6">
                                <div>
                                    <p
                                        class="text-sm font-medium text-slate-500"
                                    >
                                        Gestão responsável
                                    </p>
                                    <h2
                                        class="mt-2 text-2xl font-semibold tracking-tight text-slate-900"
                                    >
                                        Cada item, bem acompanhado.
                                    </h2>
                                </div>
                                <span
                                    class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-800"
                                    aria-hidden="true"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        class="size-6"
                                    >
                                        <path
                                            d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z"
                                        />
                                        <path
                                            d="m4.5 7.8 7.5 4.4 7.5-4.4M12 12.5V21"
                                        />
                                    </svg>
                                </span>
                            </div>

                            <div class="mt-8 space-y-4">
                                <div
                                    class="flex items-center gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4"
                                >
                                    <span
                                        class="flex size-10 items-center justify-center rounded-lg bg-sky-100 text-sky-900"
                                        aria-hidden="true"
                                    >
                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            class="size-5"
                                        >
                                            <rect
                                                x="3"
                                                y="5"
                                                width="18"
                                                height="14"
                                                rx="2"
                                            />
                                            <path d="M8 9h8M8 13h5" />
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            Inventário organizado
                                        </p>
                                        <p
                                            class="mt-0.5 text-sm text-slate-500"
                                        >
                                            Nome, marca e estado atual em um só
                                            lugar.
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4"
                                >
                                    <span
                                        class="flex size-10 items-center justify-center rounded-lg bg-rose-100 text-rose-900"
                                        aria-hidden="true"
                                    >
                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            class="size-5"
                                        >
                                            <circle cx="9" cy="8" r="3" />
                                            <path
                                                d="M3.5 19v-1.5A4.5 4.5 0 0 1 8 13h2a4.5 4.5 0 0 1 4.5 4.5V19M16 5.5a3 3 0 0 1 0 5.8M17 13.2a4.5 4.5 0 0 1 3.5 4.4V19"
                                            />
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            Responsabilidades visíveis
                                        </p>
                                        <p
                                            class="mt-0.5 text-sm text-slate-500"
                                        >
                                            Pessoas associadas aos equipamentos
                                            cadastrados.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-7 flex items-center gap-3 border-t border-slate-100 pt-6"
                            >
                                <span
                                    class="h-1.5 flex-1 rounded-full bg-sky-700"
                                />
                                <span
                                    class="h-1.5 w-10 rounded-full bg-emerald-300"
                                />
                                <span
                                    class="h-1.5 w-7 rounded-full bg-rose-300"
                                />
                                <span
                                    class="h-1.5 w-5 rounded-full bg-amber-300"
                                />
                                <span class="sr-only"
                                    >Cores institucionais do portal</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section
                class="mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-20"
                aria-labelledby="portal-benefits"
            >
                <div class="max-w-2xl">
                    <p
                        class="text-sm font-semibold tracking-wide text-sky-900 uppercase"
                    >
                        Um portal para o dia a dia
                    </p>
                    <h2
                        id="portal-benefits"
                        class="mt-3 text-3xl font-semibold tracking-tight text-slate-950"
                    >
                        Informação acessível. Patrimônio acompanhado.
                    </h2>
                </div>
                <div class="mt-10 grid gap-5 md:grid-cols-3">
                    <article
                        class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
                    >
                        <span
                            class="mb-5 flex size-10 items-center justify-center rounded-lg bg-sky-100 font-semibold text-sky-900"
                            aria-hidden="true"
                        >
                            01
                        </span>
                        <h3 class="font-semibold text-slate-900">
                            Registre equipamentos
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Mantenha os dados essenciais e o estado atual dos
                            equipamentos organizados.
                        </p>
                    </article>
                    <article
                        class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
                    >
                        <span
                            class="mb-5 flex size-10 items-center justify-center rounded-lg bg-emerald-100 font-semibold text-emerald-900"
                            aria-hidden="true"
                        >
                            02
                        </span>
                        <h3 class="font-semibold text-slate-900">
                            Identifique responsáveis
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Consulte o cadastro de pessoas e associe
                            responsáveis aos itens.
                        </p>
                    </article>
                    <article
                        class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
                    >
                        <span
                            class="mb-5 flex size-10 items-center justify-center rounded-lg bg-amber-100 font-semibold text-amber-900"
                            aria-hidden="true"
                        >
                            03
                        </span>
                        <h3 class="font-semibold text-slate-900">
                            Encontre informações
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Use a pesquisa para localizar registros de
                            equipamentos e pessoas.
                        </p>
                    </article>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-200 bg-white">
            <div
                class="mx-auto flex max-w-7xl flex-col gap-2 px-6 py-6 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-8"
            >
                <p class="font-medium text-slate-700">
                    APG/TIC · Governo da Bahia
                </p>
                <p>Portal de registro e controle de equipamentos.</p>
            </div>
        </footer>
    </div>
</template>
