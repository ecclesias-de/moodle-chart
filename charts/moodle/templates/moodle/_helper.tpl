{{/*
Basename for moodle resources
{{ include "moodle.basename" . }}
*/}}
{{- define "moodle.basename" -}}
moodle-{{ include "basename" . }}
{{- end -}}

{{/*
Kubernetes standard labels for moodle
labels: {{ include "moodle.labels" . | nindent 4 }}
*/}}
{{- define "moodle.labels" -}}
{{ include "labels" . }}
app.kubernetes.io/component: moodle
# app.metaways.net/software: 
app.kubernetes.io/version: {{ .Values.moodle.deployment.image.tag | quote }}
{{- end -}}

{{/*
Labels used to match moodle labels
selector: {{- include "moodle.matchLabels" . | nindent 4 }}
*/}}
{{- define "moodle.matchLabels" -}}
{{include "matchLabels" . }}
app.kubernetes.io/component: moodle
{{- end -}}

{{/*
Secret name
secretName: {{ template "moodle.secret" . }}
*/}}
{{- define "moodle.secret" -}}
{{- if .Values.moodle.secrets.existingSecret -}}
{{ .Values.moodle.secrets.existingSecret }}
{{- else -}}
{{ include "moodle.basename" . }}
{{- end -}}
{{- end -}}

{{/*
Pvc name
claimName: {{ template "moodle.pvc" . }}
*/}}
{{- define "moodle.pvc" -}}
{{- if .Values.moodle.persistence.existingPvc -}}
{{ .Values.moodle.persistence.existingPvc }}
{{- else -}}
{{ include "moodle.basename" . }}
{{- end -}}
{{- end -}}