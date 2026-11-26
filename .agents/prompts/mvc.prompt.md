---
agent: "lyra-expert"
description: "Plan and create an MVC task"
---

Create a work plan for generating MVC pages based on the user's requirements, or execute the MVC creation work based on an existing plan.

## Read First

- `../instructions/windwalker.instructions.md` Windwalker development guide
- `../skills/lyrasoft-best-practice/references/flow/mvc-planning.md` MVC planning flow

## Notes

- Unless the user instructs otherwise, do not create translation files at the start; write UI text directly in the language set by the project's app locale.
- At the start, only produce the plan, unless the user asks to execute it.
- For the backend, once the pages are generated you may add them to the menu first so the user can review the results early.
