<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { nextTick, ref, watch } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface PersonOption {
    id: number;
    name: string;
}

interface EquipmentRecord {
    id: number;
    name: string;
    brand: string;
    condition_description: string;
    responsible_person: PersonOption | null;
}

const equipmentFields = [
    { key: 'name', label: 'Equipamento', type: 'text' },
    { key: 'brand', label: 'Marca', type: 'text' },
    { key: 'condition_description', label: 'Estado atual', type: 'text' },
] as const;
type EquipmentField = (typeof equipmentFields)[number]['key'];

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
    equipment: Paginated<EquipmentRecord>;
    people: PersonOption[];
    filters: {
        search: string;
    };
}>();

const form = useForm({
    name: '',
    brand: '',
    condition_description: '',
    responsible_person_id: null as number | null,
});
const responsibleForm = useForm({
    responsible_person_id: null as number | null,
});
const editForm = useForm({
    value: '',
});
const editingField = ref<{ id: number; field: EquipmentField } | null>(null);
const editingEquipmentId = ref<number | null>(null);
const searchTerm = ref(props.filters.search);

watch(
    () => props.filters.search,
    (value) => {
        searchTerm.value = value;
    },
);

function registerEquipment(): void {
    form.post('/equipment', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

async function editEquipmentField(
    item: EquipmentRecord,
    field: EquipmentField,
): Promise<void> {
    cancelResponsibleEdit();
    editForm.reset();
    editForm.clearErrors();
    editForm.value = item[field];
    editingField.value = { id: item.id, field };
    await nextTick();
    document.getElementById(`equipment-field-${item.id}-${field}`)?.focus();
}

function isEditingEquipmentField(
    equipmentId: number,
    field: EquipmentField,
): boolean {
    return (
        editingField.value?.id === equipmentId &&
        editingField.value.field === field
    );
}

function cancelEquipmentFieldEdit(): void {
    editingField.value = null;
    editForm.reset();
    editForm.clearErrors();
}

function updateEquipmentField(): void {
    const target = editingField.value;

    if (target === null) {
        return;
    }

    editForm.patch(`/equipment/${target.id}/fields/${target.field}`, {
        preserveScroll: true,
        onSuccess: () => {
            editingField.value = null;
            editForm.reset();
        },
    });
}

function editResponsiblePerson(item: EquipmentRecord): void {
    editingField.value = null;
    editForm.reset();
    editForm.clearErrors();
    responsibleForm.reset();
    responsibleForm.clearErrors();
    responsibleForm.responsible_person_id = item.responsible_person?.id ?? null;
    editingEquipmentId.value = item.id;
    void nextTick(() =>
        document.getElementById(`equipment-responsible-${item.id}`)?.focus(),
    );
}

function toggleResponsibleEdit(item: EquipmentRecord): void {
    if (editingEquipmentId.value === item.id) {
        cancelResponsibleEdit();

        return;
    }

    editResponsiblePerson(item);
}

function cancelResponsibleEdit(): void {
    editingEquipmentId.value = null;
    responsibleForm.reset();
    responsibleForm.clearErrors();
}

function updateResponsiblePerson(): void {
    const equipmentId = editingEquipmentId.value;

    if (equipmentId === null) {
        return;
    }

    responsibleForm.patch(`/equipment/${equipmentId}/responsible-person`, {
        preserveScroll: true,
        onSuccess: () => {
            editingEquipmentId.value = null;
            responsibleForm.reset();
        },
    });
}

function searchEquipment(): void {
    router.get(
        '/equipment',
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
    <Head title="Cadastro de equipamentos" />

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
                    Cadastro de equipamentos
                </h1>
                <p class="mt-2 text-sm text-muted-foreground">
                    Consulte o inventário e acompanhe o estado dos equipamentos.
                </p>
            </div>
            <nav class="flex gap-2 text-sm" aria-label="Seções do portal">
                <Link
                    href="/equipment"
                    class="rounded-md bg-sky-100 px-3 py-2 font-medium text-sky-900"
                    aria-current="page"
                >
                    Equipamentos
                </Link>
                <Link
                    href="/people"
                    class="rounded-md px-3 py-2 text-muted-foreground hover:bg-muted"
                >
                    Pessoas
                </Link>
            </nav>
        </div>

        <section
            class="rounded-xl border border-t-4 border-t-sky-300 bg-card p-5 shadow-sm md:p-6"
            aria-labelledby="equipment-form-heading"
        >
            <div class="mb-5">
                <h2
                    id="equipment-form-heading"
                    class="text-lg font-semibold text-foreground"
                >
                    Registrar equipamento
                </h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Informe os dados do item e selecione uma pessoa responsável,
                    se aplicável.
                </p>
            </div>
            <form
                class="grid gap-4 sm:grid-cols-2"
                @submit.prevent="registerEquipment"
            >
                <div class="grid gap-2">
                    <Label for="equipment-name">Nome do equipamento</Label>
                    <Input
                        id="equipment-name"
                        v-model="form.name"
                        name="name"
                        required
                        :aria-invalid="Boolean(form.errors.name)"
                    />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="equipment-brand">Marca</Label>
                    <Input
                        id="equipment-brand"
                        v-model="form.brand"
                        name="brand"
                        required
                        :aria-invalid="Boolean(form.errors.brand)"
                    />
                    <InputError :message="form.errors.brand" />
                </div>
                <div class="grid gap-2 sm:col-span-2">
                    <Label for="equipment-condition">
                        Descrição do estado atual
                    </Label>
                    <textarea
                        id="equipment-condition"
                        v-model="form.condition_description"
                        name="condition_description"
                        rows="3"
                        required
                        :aria-invalid="
                            Boolean(form.errors.condition_description)
                        "
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-xs transition outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring/50 aria-[invalid=true]:border-destructive"
                    />
                    <InputError :message="form.errors.condition_description" />
                </div>
                <div class="grid gap-2 sm:col-span-2">
                    <Label for="equipment-person">Pessoa responsável</Label>
                    <select
                        id="equipment-person"
                        v-model="form.responsible_person_id"
                        name="responsible_person_id"
                        :aria-invalid="
                            Boolean(form.errors.responsible_person_id)
                        "
                        class="h-10 w-full rounded-md border border-input bg-background px-3 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring/50 aria-[invalid=true]:border-destructive"
                    >
                        <option :value="null">Sem responsável</option>
                        <option
                            v-for="person in people"
                            :key="person.id"
                            :value="person.id"
                        >
                            {{ person.name }}
                        </option>
                    </select>
                    <InputError :message="form.errors.responsible_person_id" />
                </div>
                <div class="sm:col-span-2">
                    <Button
                        type="submit"
                        class="bg-sky-800 text-white hover:bg-sky-900"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing ? 'Salvando…' : 'Salvar equipamento'
                        }}
                    </Button>
                </div>
            </form>
        </section>

        <section
            class="overflow-hidden rounded-xl border border-t-4 border-t-emerald-200 bg-card shadow-sm"
            aria-labelledby="equipment-list-heading"
        >
            <div
                class="flex flex-col gap-4 border-b p-5 md:flex-row md:items-center md:justify-between md:p-6"
            >
                <div>
                    <h2
                        id="equipment-list-heading"
                        class="text-lg font-semibold text-foreground"
                    >
                        Equipamentos cadastrados
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ equipment.total }}
                        {{
                            equipment.total === 1
                                ? 'equipamento registrado'
                                : 'equipamentos registrados'
                        }}
                    </p>
                </div>
                <form
                    class="flex w-full gap-2 md:max-w-md"
                    role="search"
                    @submit.prevent="searchEquipment"
                >
                    <Input
                        v-model="searchTerm"
                        type="search"
                        name="search"
                        aria-label="Pesquisar equipamentos"
                        placeholder="Buscar por nome, marca, estado ou responsável"
                    />
                    <Button type="submit" variant="outline">Pesquisar</Button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="bg-muted/50 text-xs text-muted-foreground">
                        <tr>
                            <th
                                v-for="field in equipmentFields"
                                :key="field.key"
                                class="px-5 py-3 font-medium"
                            >
                                {{ field.label }}
                            </th>
                            <th class="px-5 py-3 font-medium">Responsável</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="item in equipment.data"
                            :key="item.id"
                            class="hover:bg-muted/30"
                        >
                            <td
                                v-for="field in equipmentFields"
                                :key="field.key"
                                class="px-5 py-4 text-muted-foreground"
                            >
                                <form
                                    v-if="
                                        isEditingEquipmentField(
                                            item.id,
                                            field.key,
                                        )
                                    "
                                    class="min-w-36 space-y-2"
                                    @submit.prevent="updateEquipmentField"
                                    @keydown.esc.prevent.stop="
                                        cancelEquipmentFieldEdit
                                    "
                                >
                                    <Label
                                        class="sr-only"
                                        :for="`equipment-field-${item.id}-${field.key}`"
                                    >
                                        Editar {{ field.label }} de
                                        {{ item.name }}
                                    </Label>
                                    <textarea
                                        v-if="
                                            field.key ===
                                            'condition_description'
                                        "
                                        :id="`equipment-field-${item.id}-${field.key}`"
                                        v-model="editForm.value"
                                        rows="3"
                                        :aria-invalid="
                                            Boolean(editForm.errors.value)
                                        "
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring/50 aria-[invalid=true]:border-destructive"
                                    />
                                    <Input
                                        v-else
                                        :id="`equipment-field-${item.id}-${field.key}`"
                                        v-model="editForm.value"
                                        :type="field.type"
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
                                            @click="cancelEquipmentFieldEdit"
                                        >
                                            Cancelar
                                        </Button>
                                    </div>
                                </form>
                                <button
                                    v-else
                                    type="button"
                                    class="rounded-sm text-left font-medium text-sky-800 underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700"
                                    :aria-label="`Editar ${field.label} de ${item.name}`"
                                    @click="editEquipmentField(item, field.key)"
                                >
                                    {{ item[field.key] }}
                                </button>
                            </td>
                            <td class="px-5 py-4 text-muted-foreground">
                                <button
                                    v-if="item.responsible_person"
                                    type="button"
                                    class="rounded-sm text-left font-medium text-sky-800 underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700"
                                    :aria-label="`Alterar responsável de ${item.name}: ${item.responsible_person.name}`"
                                    :aria-expanded="
                                        editingEquipmentId === item.id
                                    "
                                    @click="toggleResponsibleEdit(item)"
                                >
                                    {{ item.responsible_person.name }}
                                </button>
                                <button
                                    v-else
                                    type="button"
                                    class="rounded-sm text-left font-medium text-sky-800 underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700"
                                    :aria-label="`Atribuir responsável a ${item.name}`"
                                    :aria-expanded="
                                        editingEquipmentId === item.id
                                    "
                                    @click="toggleResponsibleEdit(item)"
                                >
                                    Sem responsável
                                </button>
                                <form
                                    v-if="editingEquipmentId === item.id"
                                    class="min-w-48 space-y-2"
                                    @submit.prevent="updateResponsiblePerson"
                                    @keydown.esc.prevent.stop="
                                        cancelResponsibleEdit
                                    "
                                >
                                    <Label
                                        class="sr-only"
                                        :for="`equipment-responsible-${item.id}`"
                                    >
                                        Pessoa responsável por {{ item.name }}
                                    </Label>
                                    <select
                                        :id="`equipment-responsible-${item.id}`"
                                        v-model="
                                            responsibleForm.responsible_person_id
                                        "
                                        name="responsible_person_id"
                                        :aria-invalid="
                                            Boolean(
                                                responsibleForm.errors
                                                    .responsible_person_id,
                                            )
                                        "
                                        class="h-10 w-full rounded-md border border-input bg-background px-3 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring/50 aria-[invalid=true]:border-destructive"
                                    >
                                        <option :value="null">
                                            Sem responsável
                                        </option>
                                        <option
                                            v-for="person in people"
                                            :key="person.id"
                                            :value="person.id"
                                        >
                                            {{ person.name }}
                                        </option>
                                    </select>
                                    <InputError
                                        :message="
                                            responsibleForm.errors
                                                .responsible_person_id
                                        "
                                    />
                                    <div class="flex gap-2">
                                        <Button
                                            type="submit"
                                            :disabled="
                                                responsibleForm.processing
                                            "
                                        >
                                            {{
                                                responsibleForm.processing
                                                    ? 'Salvando…'
                                                    : 'Salvar'
                                            }}
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            :disabled="
                                                responsibleForm.processing
                                            "
                                            @click="cancelResponsibleEdit"
                                        >
                                            Cancelar
                                        </Button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <tr v-if="equipment.data.length === 0">
                            <td
                                colspan="4"
                                class="px-5 py-12 text-center text-muted-foreground"
                                aria-live="polite"
                            >
                                Nenhum equipamento encontrado. Ajuste a pesquisa
                                ou cadastre um equipamento.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                class="flex flex-col gap-3 border-t px-5 py-4 text-sm sm:flex-row sm:items-center sm:justify-between"
            >
                <p class="text-muted-foreground">
                    Exibindo {{ equipment.from ?? 0 }}–{{
                        equipment.to ?? 0
                    }}
                    de {{ equipment.total }}
                </p>
                <nav
                    v-if="equipment.last_page > 1"
                    class="flex flex-wrap gap-1"
                    aria-label="Paginação de equipamentos"
                >
                    <template
                        v-for="link in equipment.links"
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
