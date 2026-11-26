<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { nextTick, ref, watch } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface PersonRecord {
    id: number;
    name: string;
    phone: string;
    email: string;
    age: number;
}

const personFields = [
    { key: 'name', label: 'Nome', type: 'text' },
    { key: 'phone', label: 'Telefone', type: 'tel' },
    { key: 'email', label: 'E-mail', type: 'email' },
    { key: 'age', label: 'Idade', type: 'number' },
] as const;
type PersonField = (typeof personFields)[number]['key'];

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface Paginated<T> {
    data: T[];
    from: number | null;
    to: number | null;
    total: number;
    last_page: number;
    links: PaginationLink[];
}

const props = defineProps<{
    people: Paginated<PersonRecord>;
    filters: {
        search: string;
    };
}>();

const form = useForm({
    name: '',
    phone: '',
    email: '',
    age: '',
});
const editForm = useForm({
    value: '' as string | number,
});
const editingField = ref<{ id: number; field: PersonField } | null>(null);
const searchTerm = ref(props.filters.search);

watch(
    () => props.filters.search,
    (value) => {
        searchTerm.value = value;
    },
);

function registerPerson(): void {
    form.post('/people', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

async function editPersonField(
    person: PersonRecord,
    field: PersonField,
): Promise<void> {
    editForm.reset();
    editForm.clearErrors();
    editForm.value = person[field];
    editingField.value = { id: person.id, field };
    await nextTick();
    document.getElementById(`person-field-${person.id}-${field}`)?.focus();
}

function isEditingPersonField(personId: number, field: PersonField): boolean {
    return (
        editingField.value?.id === personId &&
        editingField.value.field === field
    );
}

function cancelPersonFieldEdit(): void {
    editingField.value = null;
    editForm.reset();
    editForm.clearErrors();
}

function updatePersonField(): void {
    const target = editingField.value;

    if (target === null) {
        return;
    }

    editForm.patch(`/people/${target.id}/fields/${target.field}`, {
        preserveScroll: true,
        onSuccess: () => {
            editingField.value = null;
            editForm.reset();
        },
    });
}

function searchPeople(): void {
    router.get(
        '/people',
        { search: searchTerm.value },
        { preserveScroll: true, preserveState: true, replace: true },
    );
}

function paginationLabel(label: string): string {
    return label
        .replace('&laquo; Previous', 'Anterior')
        .replace('Next &raquo;', 'Próxima');
}
</script>

<template>
    <Head title="Cadastro de pessoas" />

    <main
        class="portal-registry-page flex min-h-screen flex-col gap-6 bg-background p-4 text-foreground md:p-8"
    >
        <div
            class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
        >
            <div>
                <p
                    class="inline-flex items-center gap-2 text-sm font-medium text-sky-800"
                >
                    <span
                        class="size-2 rounded-full bg-amber-300"
                        aria-hidden="true"
                    />
                    APG/TIC · Governo da Bahia
                </p>
                <h1 class="mt-1 text-3xl font-semibold tracking-tight">
                    Cadastro de pessoas
                </h1>
                <p class="mt-2 text-sm text-muted-foreground">
                    Gerencie as pessoas que podem ser responsáveis por
                    equipamentos.
                </p>
            </div>
            <nav class="flex gap-2 text-sm" aria-label="Seções do portal">
                <Link
                    href="/equipment"
                    class="rounded-md px-3 py-2 text-muted-foreground hover:bg-muted"
                >
                    Equipamentos
                </Link>
                <Link
                    href="/people"
                    class="rounded-md bg-sky-100 px-3 py-2 font-medium text-sky-900"
                    aria-current="page"
                >
                    Pessoas
                </Link>
            </nav>
        </div>

        <section
            class="rounded-xl border border-t-4 border-t-sky-300 bg-card p-5 shadow-sm md:p-6"
            aria-labelledby="person-form-heading"
        >
            <div class="mb-5">
                <h2
                    id="person-form-heading"
                    class="text-lg font-semibold text-foreground"
                >
                    Registrar pessoa
                </h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Preencha os dados de contato e a idade.
                </p>
            </div>
            <form
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4"
                @submit.prevent="registerPerson"
            >
                <div class="grid gap-2">
                    <Label for="person-name">Nome</Label>
                    <Input
                        id="person-name"
                        v-model="form.name"
                        name="name"
                        autocomplete="name"
                        required
                        :aria-invalid="Boolean(form.errors.name)"
                    />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="person-phone">Contato telefônico</Label>
                    <Input
                        id="person-phone"
                        v-model="form.phone"
                        name="phone"
                        type="tel"
                        autocomplete="tel"
                        required
                        :aria-invalid="Boolean(form.errors.phone)"
                    />
                    <InputError :message="form.errors.phone" />
                </div>
                <div class="grid gap-2">
                    <Label for="person-email">E-mail</Label>
                    <Input
                        id="person-email"
                        v-model="form.email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        :aria-invalid="Boolean(form.errors.email)"
                    />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="grid gap-2">
                    <Label for="person-age">Idade</Label>
                    <Input
                        id="person-age"
                        v-model="form.age"
                        name="age"
                        type="number"
                        min="0"
                        step="1"
                        required
                        :aria-invalid="Boolean(form.errors.age)"
                    />
                    <InputError :message="form.errors.age" />
                </div>
                <div class="sm:col-span-2 lg:col-span-4">
                    <Button
                        type="submit"
                        class="bg-sky-800 text-white hover:bg-sky-900"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Salvando…' : 'Salvar pessoa' }}
                    </Button>
                </div>
            </form>
        </section>

        <section
            class="overflow-hidden rounded-xl border border-t-4 border-t-emerald-200 bg-card shadow-sm"
            aria-labelledby="people-list-heading"
        >
            <div
                class="flex flex-col gap-4 border-b p-5 md:flex-row md:items-center md:justify-between md:p-6"
            >
                <div>
                    <h2
                        id="people-list-heading"
                        class="text-lg font-semibold text-foreground"
                    >
                        Pessoas cadastradas
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ people.total }}
                        {{
                            people.total === 1
                                ? 'pessoa registrada'
                                : 'pessoas registradas'
                        }}
                    </p>
                </div>
                <form
                    class="flex w-full gap-2 md:max-w-md"
                    role="search"
                    @submit.prevent="searchPeople"
                >
                    <Input
                        v-model="searchTerm"
                        type="search"
                        name="search"
                        aria-label="Pesquisar pessoas"
                        placeholder="Buscar por nome, telefone ou e-mail"
                    />
                    <Button type="submit" variant="outline">Pesquisar</Button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="bg-muted/50 text-xs text-muted-foreground">
                        <tr>
                            <th
                                v-for="field in personFields"
                                :key="field.key"
                                class="px-5 py-3 font-medium"
                            >
                                {{ field.label }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="person in people.data"
                            :key="person.id"
                            class="hover:bg-muted/30"
                        >
                            <td
                                v-for="field in personFields"
                                :key="field.key"
                                class="px-5 py-4 text-muted-foreground"
                            >
                                <form
                                    v-if="
                                        isEditingPersonField(
                                            person.id,
                                            field.key,
                                        )
                                    "
                                    class="min-w-36 space-y-2"
                                    @submit.prevent="updatePersonField"
                                    @keydown.esc.prevent.stop="
                                        cancelPersonFieldEdit
                                    "
                                >
                                    <Label
                                        class="sr-only"
                                        :for="`person-field-${person.id}-${field.key}`"
                                    >
                                        Editar {{ field.label }} de
                                        {{ person.name }}
                                    </Label>
                                    <Input
                                        :id="`person-field-${person.id}-${field.key}`"
                                        v-model="editForm.value"
                                        :type="field.type"
                                        :min="
                                            field.key === 'age' ? 0 : undefined
                                        "
                                        :step="
                                            field.key === 'age' ? 1 : undefined
                                        "
                                        :aria-invalid="
                                            Boolean(editForm.errors.value)
                                        "
                                    />
                                    <InputError
                                        :message="editForm.errors.value"
                                    />
                                    <div class="flex gap-2">
                                        <Button
                                            type="submit"
                                            :disabled="editForm.processing"
                                        >
                                            {{
                                                editForm.processing
                                                    ? 'Salvando…'
                                                    : 'Salvar'
                                            }}
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            :disabled="editForm.processing"
                                            @click="cancelPersonFieldEdit"
                                        >
                                            Cancelar
                                        </Button>
                                    </div>
                                </form>
                                <button
                                    v-else
                                    type="button"
                                    class="rounded-sm text-left font-medium text-sky-800 underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700"
                                    :aria-label="`Editar ${field.label} de ${person.name}`"
                                    @click="editPersonField(person, field.key)"
                                >
                                    {{ person[field.key] }}
                                </button>
                            </td>
                        </tr>
                        <tr v-if="people.data.length === 0">
                            <td
                                colspan="4"
                                class="px-5 py-12 text-center text-muted-foreground"
                                aria-live="polite"
                            >
                                Nenhuma pessoa encontrada. Ajuste a pesquisa ou
                                cadastre uma pessoa.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                class="flex flex-col gap-3 border-t px-5 py-4 text-sm sm:flex-row sm:items-center sm:justify-between"
            >
                <p class="text-muted-foreground">
                    Exibindo {{ people.from ?? 0 }}–{{ people.to ?? 0 }} de
                    {{ people.total }}
                </p>
                <nav
                    v-if="people.last_page > 1"
                    class="flex flex-wrap gap-1"
                    aria-label="Paginação de pessoas"
                >
                    <template
                        v-for="link in people.links"
                        :key="`${link.label}-${link.active}`"
                    >
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="rounded-md border px-3 py-1.5 transition hover:bg-muted"
                            :class="
                                link.active
                                    ? 'border-sky-800 bg-sky-800 text-white hover:bg-sky-900'
                                    : 'text-muted-foreground'
                            "
                        >
                            {{ paginationLabel(link.label) }}
                        </Link>
                        <span
                            v-else
                            class="rounded-md border px-3 py-1.5 text-muted-foreground/50"
                        >
                            {{ paginationLabel(link.label) }}
                        </span>
                    </template>
                </nav>
            </div>
        </section>
    </main>
</template>
