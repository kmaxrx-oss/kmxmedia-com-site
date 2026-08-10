export type WorkRequestThemeId = 'starglass' | 'kmx';

export interface WorkRequestTheme {
  id: WorkRequestThemeId;
  page: string;
  nav: string;
  eyebrow: string;
  heading: string;
  body: string;
  muted: string;
  trustPanel: string;
  link: string;
  fieldShell: string;
  label: string;
  helper: string;
  input: string;
  choice: string;
  checkbox: string;
  panel: string;
  panelEyebrow: string;
  panelAccent: string;
  submit: string;
  statusError: string;
  statusWarn: string;
}

export const workRequestThemes: Record<WorkRequestThemeId, WorkRequestTheme> = {
  starglass: {
    id: 'starglass',
    page: 'bg-slate-950 text-slate-100',
    nav: 'border-slate-800',
    eyebrow: 'text-cyan-400/90',
    heading: 'text-white',
    body: 'text-slate-300',
    muted: 'text-slate-400',
    trustPanel: 'border-slate-700 bg-slate-900/80',
    link: 'text-cyan-400 hover:underline',
    fieldShell: 'rounded-2xl border border-slate-700 bg-slate-900/60 p-5',
    label: 'block text-base font-bold text-white',
    helper: 'mt-1 text-sm leading-6 text-slate-400',
    input:
      'mt-3 min-h-11 w-full rounded-xl border border-slate-600 bg-slate-950 px-4 py-2.5 text-base text-slate-100 placeholder:text-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30',
    choice:
      'flex min-h-11 cursor-pointer items-start gap-3 rounded-xl border border-slate-600 bg-slate-950/80 px-4 py-3 text-sm leading-5 text-slate-200 transition hover:border-cyan-600/60 has-[:checked]:border-cyan-500 has-[:checked]:bg-cyan-950/40',
    checkbox: 'mt-0.5 h-4 w-4 accent-cyan-500',
    panel: 'rounded-2xl border border-slate-700 bg-slate-900/90 p-5 text-slate-100 shadow-lg',
    panelEyebrow: 'text-cyan-400/90',
    panelAccent: 'text-cyan-400',
    submit:
      'w-full rounded-xl bg-cyan-500 px-6 py-4 text-base font-bold text-slate-950 transition hover:bg-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-slate-950',
    statusError: 'rounded-xl border border-red-500/50 bg-red-950/50 px-4 py-3 text-sm text-red-200',
    statusWarn: 'rounded-xl border border-amber-500/50 bg-amber-950/50 px-4 py-3 text-sm text-amber-200',
  },
  kmx: {
    id: 'kmx',
    page: 'bg-[#fbfaf6] text-[#22332d]',
    nav: 'border-[#e0d5c5]',
    eyebrow: 'text-[#a95530]',
    heading: 'text-[#22332d]',
    body: 'text-[#596861]',
    muted: 'text-[#66736d]',
    trustPanel: 'border-[#e0d5c5] bg-white shadow-sm',
    link: 'font-semibold text-[#a95530] hover:underline',
    fieldShell: 'rounded-[1.4rem] border border-[#e0d5c5] bg-white p-5 shadow-sm',
    label: 'block text-base font-black tracking-[-0.01em] text-[#22332d]',
    helper: 'mt-2 text-sm leading-6 text-[#66736d]',
    input:
      'mt-3 min-h-12 w-full rounded-2xl border border-[#d4cab8] bg-[#fffdf8] px-4 py-3 text-base text-[#22332d] shadow-sm transition placeholder:text-[#9b9184] focus:border-[#a95530] focus:outline-solid focus:outline-3 focus:outline-[#a95530] focus:outline-offset-3 focus:ring-4 focus:ring-[#f1c29b]/70',
    choice:
      'flex min-h-12 cursor-pointer items-start gap-3 rounded-2xl border border-[#e0d5c5] bg-[#fffdf8] px-4 py-3 text-sm leading-5 text-[#394941] transition hover:border-[#c66b3d] has-[:checked]:border-[#c66b3d] has-[:checked]:bg-[#fff1e7]',
    checkbox: 'mt-0.5 h-5 w-5 accent-[#c66b3d]',
    panel:
      'rounded-[1.6rem] border border-[#d4cab8] bg-[#22332d] p-5 text-[#fbfaf6] shadow-[0_20px_50px_rgba(34,51,45,0.18)]',
    panelEyebrow: 'text-[#e0b08e]',
    panelAccent: 'text-[#e0b08e]',
    submit:
      'w-full rounded-full bg-[#c66b3d] px-6 py-4 text-base font-black text-white transition hover:bg-[#a95530] focus:outline-solid focus:outline-3 focus:outline-[#a95530] focus:outline-offset-3',
    statusError: 'rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800',
    statusWarn: 'rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800',
  },
};