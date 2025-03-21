<template>
	<div ref="markdownInterface" class="interface-input-rich-text-md" :class="view[0]">
		<div class="toolbar">
			<template v-if="view[0] !== 'preview'">
				<v-menu
					v-if="toolbar.includes('heading')"
					show-arrow
					placement="bottom-start"
					:class="[{ active: view[0] !== 'preview' }]"
				>
					<template #activator="{ toggle }">
						<v-button v-tooltip="t('wysiwyg_options.heading')" small icon @click="toggle">
							<v-icon name="format_size" />
						</v-button>
					</template>
					<v-list>
						<v-list-item v-for="n in 6" :key="n" clickable @click="edit('heading', { level: n })">
							<v-list-item-content><v-text-overflow :text="t(`wysiwyg_options.h${n}`)" /></v-list-item-content>
							<v-list-item-hint>{{ translateShortcut(['meta', 'alt']) }} {{ n }}</v-list-item-hint>
						</v-list-item>
					</v-list>
				</v-menu>

				<v-button
					v-if="toolbar.includes('bold')"
					v-tooltip="t('wysiwyg_options.bold') + ' - ' + translateShortcut(['meta', 'b'])"
					small
					icon
					@click="edit('bold')"
				>
					<v-icon name="format_bold" />
				</v-button>
				<v-button
					v-if="toolbar.includes('italic')"
					v-tooltip="t('wysiwyg_options.italic') + ' - ' + translateShortcut(['meta', 'i'])"
					small
					icon
					@click="edit('italic')"
				>
					<v-icon name="format_italic" />
				</v-button>
				<v-button
					v-if="toolbar.includes('strikethrough')"
					v-tooltip="t('wysiwyg_options.strikethrough') + ' - ' + translateShortcut(['meta', 'alt', 'd'])"
					small
					icon
					@click="edit('strikethrough')"
				>
					<v-icon name="format_strikethrough" />
				</v-button>
				<v-button
					v-if="toolbar.includes('bullist')"
					v-tooltip="t('wysiwyg_options.bullist')"
					small
					icon
					@click="edit('listBulleted')"
				>
					<v-icon name="format_list_bulleted" />
				</v-button>
				<v-button
					v-if="toolbar.includes('numlist')"
					v-tooltip="t('wysiwyg_options.numlist')"
					small
					icon
					@click="edit('listNumbered')"
				>
					<v-icon name="format_list_numbered" />
				</v-button>
				<v-button
					v-if="toolbar.includes('blockquote')"
					v-tooltip="t('wysiwyg_options.blockquote') + ' - ' + translateShortcut(['meta', 'alt', 'q'])"
					small
					icon
					@click="edit('blockquote')"
				>
					<v-icon name="format_quote" />
				</v-button>
				<v-button
					v-if="toolbar.includes('code')"
					v-tooltip="t('wysiwyg_options.codeblock') + ' - ' + translateShortcut(['meta', 'alt', 'c'])"
					small
					icon
					@click="edit('code')"
				>
					<v-icon name="code" />
				</v-button>
				<v-button
					v-if="toolbar.includes('link')"
					v-tooltip="t('wysiwyg_options.link') + ' - ' + translateShortcut(['meta', 'k'])"
					small
					icon
					@click="edit('link')"
				>
					<v-icon name="insert_link" />
				</v-button>

				<v-menu v-if="toolbar.includes('table')" show-arrow :close-on-content-click="false">
					<template #activator="{ toggle }">
						<v-button v-tooltip="t('wysiwyg_options.table')" small icon @click="toggle">
							<v-icon name="table_chart" />
						</v-button>
					</template>

					<template #default="{ deactivate }">
						<div class="table-options">
							<div class="field half">
								<p class="type-label">{{ t('rows') }}</p>
								<v-input v-model="table.rows" :min="1" type="number" />
							</div>
							<div class="field half">
								<p class="type-label">{{ t('columns') }}</p>
								<v-input v-model="table.columns" :min="1" type="number" />
							</div>
							<div class="field full">
								<v-button
									full-width
									@click="
										() => {
											edit('table', table);
											deactivate();
										}
									"
								>
									Create
								</v-button>
							</div>
						</div>
					</template>
				</v-menu>

				<v-button
					v-if="toolbar.includes('image')"
					v-tooltip="t('wysiwyg_options.image')"
					small
					icon
					@click="imageDialogOpen = true"
				>
					<v-icon name="insert_photo" />
				</v-button>

				<v-button
					v-for="custom in customSyntax"
					:key="custom.name"
					v-tooltip="custom.name"
					small
					icon
					@click="edit('custom', custom)"
				>
					<v-icon :name="custom.icon" />
				</v-button>
			</template>

			<div class="spacer"></div>

			<v-item-group v-model="view" class="view" mandatory rounded>
				<v-button x-small value="editor" :class="[{ active: view[0] !== 'preview' }]">
					{{ t('interfaces.input-rich-text-md.edit') }}
				</v-button>
				<v-button x-small value="preview" :class="[{ active: view[0] === 'preview' }]">
					{{ t('interfaces.input-rich-text-md.preview') }}
				</v-button>
			</v-item-group>
		</div>

		<div ref="codemirrorEl"></div>
		<template v-if="softLength">
			<span
				class="remaining"
				:class="{
					warning: percRemaining < 10,
					danger: percRemaining < 5,
				}"
			>
				{{ softLength - count }}
			</span>
		</template>
		<div
			v-md="markdownString"
			class="preview-box"
			:style="view[0] === 'preview' ? 'display:block' : 'display:none'"
		></div>

		<v-dialog :model-value="imageDialogOpen" @esc="imageDialogOpen = null" @update:model-value="imageDialogOpen = null">
			<v-card>
				<v-card-title>{{ t('upload_from_device') }}</v-card-title>
				<v-card-text>
					<v-upload from-url from-library :folder="folder" @input="onImageUpload" />
				</v-card-text>
				<v-card-actions>
					<v-button secondary @click="imageDialogOpen = null">{{ t('cancel') }}</v-button>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>

<script lang="ts">
import { useI18n } from 'vue-i18n';
import { defineComponent, computed, ref, onMounted, watch, reactive, PropType } from 'vue';

import CodeMirror from 'codemirror';
import 'codemirror/mode/markdown/markdown';
import 'codemirror/addon/display/placeholder.js';

import { applyEdit, CustomSyntax, Alteration } from './edits';
import { getPublicURL } from '@/utils/get-root-path';
import { addTokenToURL } from '@/api';
import escapeStringRegexp from 'escape-string-regexp';
import useShortcut from '@/composables/use-shortcut';
import translateShortcut from '@/utils/translate-shortcut';
import { percentage } from '@/utils/percentage';

export default defineComponent({
	props: {
		value: {
			type: String,
			default: null,
		},
		disabled: {
			type: Boolean,
			default: false,
		},
		placeholder: {
			type: String,
			default: null,
		},
		editorFont: {
			type: String as PropType<'sans-serif' | 'serif' | 'monospace'>,
			default: 'sans-serif',
		},
		previewFont: {
			type: String as PropType<'sans-serif' | 'serif' | 'monospace'>,
			default: 'sans-serif',
		},
		toolbar: {
			type: Array as PropType<string[] | null>,
			default: () => [
				'heading',
				'bold',
				'italic',
				'strikethrough',
				'bullist',
				'numlist',
				'blockquote',
				'code',
				'link',
				'table',
				'image',
				'link',
				'empty',
			],
		},
		customSyntax: {
			type: Array as PropType<CustomSyntax[]>,
			default: () => [],
		},
		imageToken: {
			type: String,
			default: undefined,
		},
		softLength: {
			type: Number,
			default: undefined,
		},
		folder: {
			type: String,
			default: undefined,
		},
	},
	emits: ['input'],
	setup(props, { emit }) {
		const { t } = useI18n();

		const markdownInterface = ref<HTMLElement>();
		const codemirrorEl = ref<HTMLTextAreaElement>();
		let codemirror: CodeMirror.Editor | null = null;

		const view = ref(['editor']);

		const imageDialogOpen = ref(false);

		let count = ref(0);

		onMounted(async () => {
			if (codemirrorEl.value) {
				codemirror = CodeMirror(codemirrorEl.value, {
					mode: 'markdown',
					configureMouse: () => ({ addNew: false }),
					lineWrapping: true,
					placeholder: props.placeholder,
					value: props.value || '',
				});

				codemirror.on('change', (cm, { origin }) => {
					if (origin === 'setValue') return;

					const content = cm.getValue();

					emit('input', content);
				});
			}

			if (markdownInterface.value) {
				const previewBox = markdownInterface.value.getElementsByClassName('preview-box')[0];

				const observer = new MutationObserver(() => {
					count.value = previewBox.textContent?.replace('\n', '')?.length ?? 0;
				});

				const config = { characterData: true, childList: true, subtree: true };

				observer.observe(previewBox, config);
			}
		});

		watch(
			() => props.value,
			(newValue) => {
				if (!codemirror) return;

				const existingValue = codemirror.getValue();

				if (existingValue !== newValue) {
					codemirror.setValue('');
					codemirror.clearHistory();
					codemirror.setValue(newValue ?? '');
					codemirror.refresh();
				}
			}
		);

		const editFamily = computed(() => {
			return `var(--family-${props.editorFont})`;
		});

		const previewFamily = computed(() => {
			return `var(--family-${props.previewFont})`;
		});

		const markdownString = computed(() => {
			let mdString = props.value || '';

			if (!props.imageToken) {
				const baseUrl = getPublicURL() + 'assets/';
				const regex = new RegExp(`\\]\\((${escapeStringRegexp(baseUrl)}[^\\s\\)]*)`, 'gm');

				const images = Array.from(mdString.matchAll(regex));

				for (const image of images) {
					mdString = mdString.replace(image[1], addTokenToURL(image[1]));
				}
			}

			return mdString;
		});

		const table = reactive({
			rows: 4,
			columns: 4,
		});

		const percRemaining = computed(() => percentage(count.value, props.softLength));
		useShortcut('meta+b', () => edit('bold'), markdownInterface);
		useShortcut('meta+i', () => edit('italic'), markdownInterface);
		useShortcut('meta+k', () => edit('link'), markdownInterface);
		useShortcut('meta+alt+d', () => edit('strikethrough'), markdownInterface);
		useShortcut('meta+alt+q', () => edit('blockquote'), markdownInterface);
		useShortcut('meta+alt+c', () => edit('code'), markdownInterface);
		useShortcut('meta+alt+1', () => edit('heading', { level: 1 }), markdownInterface);
		useShortcut('meta+alt+2', () => edit('heading', { level: 2 }), markdownInterface);
		useShortcut('meta+alt+3', () => edit('heading', { level: 3 }), markdownInterface);
		useShortcut('meta+alt+4', () => edit('heading', { level: 4 }), markdownInterface);
		useShortcut('meta+alt+5', () => edit('heading', { level: 5 }), markdownInterface);
		useShortcut('meta+alt+6', () => edit('heading', { level: 6 }), markdownInterface);

		return {
			t,
			percRemaining,
			count,
			codemirrorEl,
			edit,
			view,
			markdownString,
			table,
			onImageUpload,
			imageDialogOpen,
			useShortcut,
			translateShortcut,
			markdownInterface,
			editFamily,
			previewFamily,
		};

		function onImageUpload(image: any) {
			if (!codemirror) return;

			let url = getPublicURL() + `assets/` + image.id;

			if (props.imageToken) {
				url += '?access_token=' + props.imageToken;
			}

			codemirror.replaceSelection(`![](${url})`);

			imageDialogOpen.value = false;
		}

		function edit(type: Alteration, options?: Record<string, any>) {
			if (codemirror) {
				applyEdit(codemirror, type, options);
			}
		}
	},
});
</script>

<style lang="scss" scoped>
@import '@/styles/mixins/form-grid';

.interface-input-rich-text-md {
	--v-button-background-color: transparent;
	--v-button-color: var(--foreground-normal);
	--v-button-background-color-hover: var(--border-normal);
	--v-button-color-hover: var(--foreground-normal);

	min-height: 300px;
	overflow: hidden;
	font-family: var(--family-sans-serif);
	border: 2px solid var(--border-normal);
	border-radius: var(--border-radius);
}

textarea {
	display: none;
}

.preview-box {
	display: none;
	padding: 20px;
}

.preview-box :deep(h1) {
	margin-top: 1em;
	margin-bottom: 0;
	color: var(--foreground-normal-alt);
	font-weight: 700;
	font-size: 36px;
	font-family: v-bind(previewFamily), serif;
	line-height: 46px;
}

.preview-box :deep(h2) {
	margin-top: 1.25em;
	margin-bottom: 0;
	color: var(--foreground-normal-alt);
	font-weight: 700;
	font-size: 24px;
	font-family: v-bind(previewFamily), serif;
	line-height: 34px;
}

.preview-box :deep(h3) {
	margin-top: 1.25em;
	margin-bottom: 0;
	color: var(--foreground-normal-alt);
	font-weight: 700;
	font-size: 19px;
	font-family: v-bind(previewFamily), serif;
	line-height: 29px;
}

.preview-box :deep(h4) {
	margin-top: 1.5em;
	margin-bottom: 0;
	color: var(--foreground-normal-alt);
	font-weight: 700;
	font-size: 16px;
	font-family: v-bind(previewFamily), serif;
	line-height: 26px;
}

.preview-box :deep(h5) {
	margin-top: 2em;
	margin-bottom: 0;
	color: var(--foreground-normal-alt);
	font-weight: 700;
	font-size: 14px;
	font-family: v-bind(previewFamily), serif;
	line-height: 24px;
}

.preview-box :deep(h6) {
	margin-top: 2em;
	margin-bottom: 0;
	color: var(--foreground-normal-alt);
	font-weight: 700;
	font-size: 12px;
	font-family: v-bind(previewFamily), serif;
	line-height: 22px;
}

.preview-box :deep(p) {
	margin: 1.5em 0;
	font-weight: 500;
	font-size: 15px;
	font-family: v-bind(previewFamily), serif;
	line-height: 24px;
}

.preview-box :deep(a) {
	color: var(--primary-125);
	text-decoration: none;
}

.preview-box :deep(ul),
.preview-box :deep(ol) {
	margin: 1.5em 0;
	font-weight: 500;
	font-size: 15px;
	font-family: v-bind(previewFamily), serif;
	line-height: 24px;
}

.remaining {
	position: absolute;
	right: 10px;
	bottom: 5px;
	color: var(--foreground-subdued);
	font-weight: 600;
	text-align: right;
	vertical-align: middle;
	font-feature-settings: 'tnum';
}

.warning {
	color: var(--warning);
}

.danger {
	color: var(--danger);
}

.preview-box :deep(ul ul),
.preview-box :deep(ol ol),
.preview-box :deep(ul ol),
.preview-box :deep(ol ul) {
	margin: 0;
}

.preview-box :deep(b),
.preview-box :deep(strong) {
	font-weight: 700;
}

.preview-box :deep(code) {
	padding: 2px 4px;
	font-weight: 500;
	font-size: 15px;
	font-family: var(--family-monospace), monospace;
	line-height: 24px;
	overflow-wrap: break-word;
	background-color: var(--background-normal);
	border-radius: var(--border-radius);
}

.preview-box :deep(pre) {
	padding: 1em;
	overflow: auto;
	font-weight: 500;
	font-size: 15px;
	font-family: var(--family-monospace), monospace;
	line-height: 24px;
	background-color: var(--background-normal);
	border-radius: var(--border-radius);
}

.preview-box :deep(blockquote) {
	margin-left: 0px;
	padding-left: 1em;
	font-weight: 500;
	font-size: 15px;
	font-family: v-bind(previewFamily), serif;
	line-height: 24px;
	border-left: 2px solid var(--border-normal);
}

.preview-box :deep(blockquote blockquote) {
	margin-left: 10px;
}

.preview-box :deep(video),
.preview-box :deep(iframe),
.preview-box :deep(img) {
	max-width: 100%;
	height: auto;
	border-radius: var(--border-radius);
}

.preview-box :deep(hr) {
	height: 1px;
	margin-top: 2em;
	margin-bottom: 2em;
	background-color: var(--border-normal);
	border: none;
}

.preview-box :deep(table) {
	font-weight: 500;
	font-size: 15px;
	line-height: 24px;
	border-collapse: collapse;
}

.preview-box :deep(table th),
.preview-box :deep(table td) {
	padding: 0.4rem;
	border: 1px solid var(--border-normal);
}

.preview-box :deep(figure) {
	display: table;
	margin: 1rem auto;
}

.preview-box :deep(figure figcaption) {
	display: block;
	margin-top: 0.25rem;
	color: #999;
	text-align: center;
}

.interface-input-rich-text-md :deep(.CodeMirror) {
	font-family: v-bind(editFamily), sans-serif;
	border: none;
	border-radius: 0;
}

.interface-input-rich-text-md :deep(.CodeMirror .CodeMirror-lines) {
	padding: 0 20px;
}

.interface-input-rich-text-md :deep(.CodeMirror .CodeMirror-lines:first-of-type) {
	margin-top: 20px;
}

.interface-input-rich-text-md :deep(.CodeMirror .CodeMirror-lines:last-of-type) {
	margin-bottom: 20px;
}

.interface-input-rich-text-md :deep(.CodeMirror .CodeMirror-scroll) {
	min-height: 260px;
}

.interface-input-rich-text-md.preview :deep(.CodeMirror) {
	display: none;
}

.toolbar {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	min-height: 40px;
	padding: 0 4px;
	background-color: var(--background-subdued);
	border-bottom: 2px solid var(--border-normal);

	.v-button + .v-button {
		margin-left: 2px;
	}

	.spacer {
		flex-grow: 1;
	}

	.view {
		--v-button-background-color: var(--border-subdued);
		--v-button-color: var(--foreground-subdued);
		--v-button-background-color-hover: var(--border-normal);
		--v-button-color-hover: var(--foreground-normal);
		--v-button-background-color-active: var(--border-normal);
		--v-button-color-active: var(--foreground-normal);
	}
}

.table-options {
	@include form-grid;

	--form-vertical-gap: 12px;
	--form-horizontal-gap: 12px;

	padding: 12px;

	.v-input {
		min-width: 100px;
	}
}
</style>
